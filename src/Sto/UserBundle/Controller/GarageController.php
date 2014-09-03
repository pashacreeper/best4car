<?php
namespace Sto\UserBundle\Controller;

use Doctrine\ORM\EntityManager;
use Sto\CoreBundle\Entity\CustomModification;
use Sto\UserBundle\Entity\UserCarImage;
use Sto\UserBundle\Form\Type\CustomModificationType;
use Sto\ContentBundle\Controller\ChoiceCityController as MainController;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Sto\UserBundle\Entity\UserCar;
use Sto\UserBundle\Form\Type\UserCarType;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Filesystem\Filesystem;

class GarageController extends MainController
{
    /**
     * @var EntityManager
     * @DI\Inject("doctrine.orm.entity_manager")
     */
    private $em;

    /**
     * Displays a form to create a new Car entity.
     *
     * @Route("/garage/new", name="garage_new")
     * @Method({"GET"})
     * @Template()
     * @Secure(roles="IS_AUTHENTICATED_FULLY")
     */
    public function newCarAction()
    {
        $car = new UserCar();
        $form = $this->createForm(new UserCarType(), $car, ['em' => $this->em]);

        return [
            'form'  => $form->createView(),
            'isNew' => true,
            'popUpError' => 0
        ];
    }

    /**
     * Creates a new Garage entity.
     *
     * @Route("/garage/create", name="garage_create")
     * @Template("StoUserBundle:Garage:newCar.html.twig")
     * @Secure(roles="IS_AUTHENTICATED_FULLY")
     */
    public function createCarAction(Request $request)
    {
        $user = $this->getUser();

        $car = new UserCar();
        $car->setUser($user);

        $this->settingCarImages($request, $car);

        $form = $this->createForm(new UserCarType(), $car, ['em' => $this->em]);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->em->persist($car);
            $this->em->flush();

            return $this->redirect(
                $this->generateUrl('fos_user_profile_show') . '#garage'
            );
        }

        return [
            'form'  => $form->createView(),
            'isNew' => true,
        ];
    }

    /**
     * Displays a form to edit Car entity.
     *
     * @Route("/garage/{id}/edit", name="garage_edit")
     * @Method({"GET"})
     * @Template("StoUserBundle:Garage:newCar.html.twig")
     * @Secure(roles="IS_AUTHENTICATED_FULLY")
     */
    public function editCarAction(UserCar $car)
    {
        $form = $this->createForm(new UserCarType(), $car, ['em' => $this->em]);

        return [
            'form'       => $form->createView(),
            'isNew'      => false,
            'car'        => $car,
            'popUpError' => 0,
        ];
    }

    /**
     * @Template()
     */
    public function renderCustomModificationFormAction(CustomModification $modification = null)
    {
        $form = $this->createForm(new CustomModificationType, $modification);

        return [
            'form' => $form->createView(),
            'id'   => ($modification) ? $modification->getId() : null,
        ];
    }

    /**
     * @Route("/garage/ajax/custom_modification/store", name="ajax_garage_custom_modification")
     * @Route("/garage/ajax/custom_modification/{id}/update", name="ajax_garage_custom_modification_update")
     * @Method("POST")
     *
     * @param Request $request
     *
     * @param CustomModification $modification
     *
     * @return JsonResponse
     */
    public function storeCustomModificationAction(Request $request, CustomModification $modification = null)
    {
        if (! $modification) {
            $modification = new CustomModification();
        }

        $form = $this->createForm(new CustomModificationType, $modification);

        $form->handleRequest($request);

        $data = [
            'error'        => true,
            'id'           => null,
            'html'         => $this->renderView('StoUserBundle:Garage:renderCustomModificationForm.html.twig', [
                'form' => $form->createView()
            ]),
            'modification' => null,
        ];

        if ($form->isValid()) {
            $this->em->persist($modification);
            $this->em->flush();

            $data = [
                'error'        => false,
                'html'         => '',
                'id'           => $modification->getId(),
                'modification' => $this->renderView('StoUserBundle:Garage:_customModificationData.html.twig', [
                        'customModification' => $modification
                    ])
            ];
        }

        return new JsonResponse($data);
    }

    /**
     * Update Garage entity.
     *
     * @Route("/garage/{id}/update", name="garage_update")
     * @Method({"POST"})
     * @Template("StoUserBundle:Garage:newCar.html.twig")
     * @Secure(roles="IS_AUTHENTICATED_FULLY")
     */
    public function updateCarAction(Request $request, UserCar $car)
    {
        $originalImages = new ArrayCollection();

        // Create an ArrayCollection of the current Image objects in the database
        foreach ($car->getImages() as $image) {
            $originalImages->add($image);
        }

        $this->settingCarImages($request, $car);

        $form = $this->createForm(new UserCarType(), $car, ['em' => $this->em]);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $car->setUpdatedtAt(new \DateTime('now'));

            foreach ($originalImages as $image) {
                if (false === $car->getImages()->contains($image)) {
                    $this->em->remove($image);
                }
            }

            $this->em->flush();

            return $this->redirect(
                $this->generateUrl('fos_user_profile_show') . '#garage'
            );
        }

        return [
            'form'    => $form->createView(),
            'isNew' => false,
            'car' => $car,
        ];
    }

    /**
     * Show garage
     *
     * @Template
     */
    public function showAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $showUser = $em->getRepository('StoUserBundle:User')->find($request->get('id'));
        $cars = $showUser->getCars();
        $oneCar = false;

        if ($cars->count() === 1) {
            return $this->render('StoUserBundle:Garage:_showCar.html.twig', [
                'car' => $cars->first(),
                'oneCar' => true,
            ]);
        }

        return compact('cars', 'showUser', 'oneCar');
    }

    /**
     * Show car
     *
     * @Template
     * @Route("/garage/{id}/car", name="garage_car_show")
     * @Method({"GET"})
     */
    public function showCarAction(UserCar $car)
    {
        $showUser = $car->getUser();

        return compact('car', 'showUser');
    }

    /**
     * Delete a Car entity.
     *
     * @Route("/garage/{id}/delete", name="garage_delete")
     * @Method({"GET"})
     * @Secure(roles="IS_AUTHENTICATED_FULLY")
     */
    public function deleteCarAction(UserCar $car)
    {
        $this->em->remove($car);
        $this->em->flush();

        return $this->redirect(
            $this->generateUrl('fos_user_profile_show') . '#garage'
        );
    }

    /**
     * @Route("/ajax/image_upload", name="ajax_garage_image_upload")
     */
    public function ajaxImageUploadAction(Request $request)
    {
        $fileSystem = new Filesystem();
        $webTmpUploadDir = '/storage/images/tmp';
        $tmpUploadFolder = $this->get('kernel')->getRootDir() . '/../web' . $webTmpUploadDir;


        if (! $fileSystem->exists($tmpUploadFolder)) {
            $fileSystem->mkdir($tmpUploadFolder);
        }

        $file = $request->files->get(0);
        $fileName = sha1(uniqid(mt_rand(), true)) . '.' . $file->guessExtension();
        $file->move($tmpUploadFolder, $fileName);

        $thumbUrl = $this->container->get('liip_imagine.cache.manager')
             ->getBrowserPath($webTmpUploadDir . '/' . $fileName, 'car_show_image_cars');

        return new JsonResponse(['img' => $thumbUrl]);
    }

    /**
     * @param Request $request
     * @param UserCar $car
     *
     * @return mixed
     */
    protected function settingCarImages(Request $request, UserCar $car)
    {
        foreach ($request->files->get('sto_user_car')['images'] as $image) {
            if ($image['image'] instanceof UploadedFile) {
                $carImage = new UserCarImage();
                $carImage->setImage($image['image']);
                $carImage->setCar($car);
                $car->addImage($carImage);

                $this->em->persist($carImage);
            }
        }
        $request->files->remove('sto_user_car');
    }
}
