<?php
namespace Sto\UserBundle\Controller;

use Sto\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sto\ContentBundle\Controller\ChoiceCityController as MainController;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Sto\UserBundle\Entity\UserCar;
use Sto\UserBundle\Form\Type\UserCarType;
use Doctrine\Common\Collections\ArrayCollection;

class GarageController extends MainController
{
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
        $form = $this->createForm(new UserCarType(), $car);

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
        $form = $this->createForm(new UserCarType(), $car);
        $form->handleRequest($request);

        $popUpError = 0;

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($car);
            $em->flush();

            return $this->redirect(
                $this->generateUrl('fos_user_profile_show') . '#garage'
            );
        }

        /**
         * @var Form $element
         */
        foreach ($form as $element) {
            if ($element->getErrors() && 'engineVolume' === $element->getName()) {
                $popUpError = 1;
            }
        }

        return [
            'form'  => $form->createView(),
            'isNew' => true,
            'popUpError' => $popUpError
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
        $form = $this->createForm(new UserCarType(), $car);

        return [
            'form'       => $form->createView(),
            'isNew'      => false,
            'car'        => $car,
            'popUpError' => 0,
        ];
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

        $form = $this->createForm(new UserCarType(), $car);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            foreach ($originalImages as $image) {
                if (false === $car->getImages()->contains($image)) {
                    $em->remove($image);
                }
            }

            $em->flush();

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

        if ($cars->count() === 1) {
            return $this->render('StoUserBundle:Garage:_showCar.html.twig', ['car' => $cars->first()]);
        }

        return compact('cars', 'showUser');
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
        $em = $this->getDoctrine()->getManager();
        $em->remove($car);
        $em->flush();

        return $this->redirect(
            $this->generateUrl('fos_user_profile_show') . '#garage'
        );
    }
}
