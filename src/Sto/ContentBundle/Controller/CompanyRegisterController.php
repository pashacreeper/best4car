<?php
namespace Sto\ContentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sto\ContentBundle\Form\RegistrationType;
use Sto\UserBundle\Entity\User;
use Symfony\Component\Form\FormError;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sto\CoreBundle\Entity\Company;

class CompanyRegisterController extends Controller
{
    /**
     * Registration company owner
     *
     * @Route("/registration/company", name="registration_company_owner")
     * @Template()
     */
    public function newCompanyOwnerAction()
    {
        $user = new User();
        $form = $this->createForm(new RegistrationType('Sto\UserBundle\Entity\User'), $user);

        return [
            'user' => $user,
            'form' => $form->createView(),
        ];
    }

    /**
     * Creates a new User entity.
     *
     * @Route("/create-company-owner", name="registration_company_owner_create")
     * @Method("POST")
     * @Template("StoContentBundle:User:newCompanyOwner.html.twig")
     */
    public function createCompanyOwnerAction(Request $request)
    {
        $user  = new User();
        $form = $this->createForm(new RegistrationType('Sto\UserBundle\Entity\User'), $user);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $ratingGroup = $em->getRepository('StoUserBundle:RatingGroup')->find(1);
            $user->setRatingGroup($ratingGroup);
            $group = $em->getRepository('StoUserBundle:Group')->find(4);
            $user->setGroups([$group]);
            $user->setEnabled(true);

            $another_user = $em->getRepository('StoUserBundle:User')->findBy(['username'=>$user->getUsername()]);
            $another_email = $em->getRepository('StoUserBundle:User')->findBy(['email'=>$user->getEmail()]);
            if ($another_user || $another_email) {
                if ($another_user) {
                    $form->get('username')->addError(new FormError('Пользователь с таким ником уже зарегистрирован!'));
                }
                if ($another_email) {
                    $form->get('email')->addError(new FormError('Пользователь с таким почтовым адресом уже зарегистрирован!'));
                }

                return [
                    'user' => $user,
                    'form' => $form->createView(),
                ];
            }

            $em->persist($user);
            $em->flush();

            $this->authenticateUser($user);

            return $this->redirect($this->generateUrl('add_company'));
        }

        return [
            'user' => $user,
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/new-company/base", name="registration_company_base")
     * @Route("/new-company/{id}/base", name="registration_company_base_with_id")
     * @Template()
     */
    public function baseAction($id = null)
    {
        $em = $this->getDoctrine()->getManager();

        if (! $company = $em->getRepository('StoCoreBundle:Company')->find($id)) {
            $company = new Company();
        }

        $form = $this->createForm(new CompanyBaseType(), $company);

        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/new-company/{id}/business-profile/", name="registration_company_business_profile")
     * @Template()
     */
    public function businessProfileAction(Company $company)
    {
        $form = $this->createForm(new CompanyBuisnessProfileType(), $company);

        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/new-company/{id}/contacts", name="registration_company_contacts")
     * @Template()
     */
    public function contactsAction(Company $company)
    {
        $form = $this->createForm(new CompanyContactsType(), $company);

        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/new-company/{id}/gallery", name="registration_company_gallery")
     * @Template()
     */
    public function galleryAction(Company $company)
    {
        $form = $this->createForm(new ComapnyGalleryType(), $company);

        return [
            'form' => $form->createView()
        ];
    }

    /**
     * Registration company
     *
     * @Route("/new-company/", name="add_company")
     * @Template()
     */
    public function newCompanyAction()
    {
        $em = $this->getDoctrine()->getManager();

        if (!$this->getUser()) {
            return new Response('User Not found.', 404);
        }

        $user = $this->getUser();

        $company = new Company();
        $manager = new CompanyManager();
        $manager->setUser($user);
        $manager->setPhone($user->getPhoneNumber());
        $manager->setCompany($company);

        $company->addCompanyManager($manager);
        $cForm = $this->createForm(new CompanyType(), $company, ['em' => $em]);

        $additionalServiceTypes = $em->getRepository('StoCoreBundle:Dictionary\AdditionalService')
            ->createQueryBuilder('dictionary')
            ->getQuery()
            ->getResult()
        ;

        return [
            'additionalServiceTypes' => $additionalServiceTypes,
            'company' => $company,
            'user' => $user->getId(),
            'cForm' => $cForm->createView()
        ];
    }

    /**
     * Creates a new User entity.
     *
     * @Route("/create-company/", name="registration_company_create")
     * @Method("POST")
     * @Template("StoContentBundle:User:newCompany.html.twig")
     */
    public function createCompanyAction(Request $request)
    {
        $company  = new Company();
        $form = $this->createForm(new CompanyType(), $company, ['em'=> $em = $this->getDoctrine()->getManager()]);
        $form->bind($request);
        $user = $this->container->get('security.context')->getToken()->getUser();

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $company->setUpdatedAt(new \DateTime());

            $managers = $company->getCompanyManager();
            foreach ($managers as $value) {
                $value->setCompany($company);
            }
            $company->setCompanyManager($managers);

            $gallery = $company->getGallery();
            foreach ($gallery as $value) {
                $value->setCompany($company);
            }
            $company->setGallery($gallery);

            $em->persist($company);
            $services = $request->get('services');

            foreach ($company->getSpecializations() as $key => $item) {
                if (isset($services[$key])) {
                    $itemServices = $services[$key];
                    foreach ($item->getServices() as $oldService) {
                        $serviceId = $oldService->getService()->getId();
                        if (($serviceKey = array_search($serviceId, $itemServices)) !== false) {
                            unset($itemServices[$serviceKey]);
                        }
                    }
                    $companyServices = [];
                    foreach ($itemServices as $serviceId) {
                        $autoService = $em->getRepository('StoCoreBundle:AutoServices')->find($serviceId);
                        $service = new CompanyAutoService();
                        $service->setService($autoService);
                        $service->setSpecialization($item);
                        $companyServices[] = $service;
                        $em->persist($service);
                    }
                    foreach ($item->getServices() as $service) {
                        $companyServices[] = $service;
                    }
                    foreach ($companyServices as $companyService) {
                        if ($companyService->getService()->getParent()) {
                            $this->createCompanyServiceParent($companyServices, $companyService, $item);
                        }
                    }
                    $em->flush();
                    foreach ($companyServices as $companyService) {
                        if ($companyService->getService()->getParent()) {
                            $this->setCompanyServiceParent($companyServices, $companyService);
                        }
                    }
                }
            }

            $em->flush();

            $this->get('session')->getFlashBag()->add('notice', 'Your company was added. Login please.');

            return $this->redirect($this->generateUrl('content_company_show', ['id'=>$company->getId()]));
        }

        $additionalServiceTypes = $em->getRepository('StoCoreBundle:Dictionary\AdditionalService')
            ->createQueryBuilder('dictionary')
            ->getQuery()
            ->getResult()
        ;

        return [
            'additionalServiceTypes' => $additionalServiceTypes,
            'company' => $company,
            'user' => $user->getId(),
            'cForm' => $form->createView(),
        ];
    }

    protected function createCompanyServiceParent(&$companyServices, $companyService, $specialization)
    {
        $companyServiceParent = null;
        $service = $companyService->getService()->getParent();
        foreach ($companyServices as $seachCompanyService) {
            if ($service == $seachCompanyService->getService()) {
                $companyServiceParent = $seachCompanyService;
                break;
            }
        }
        if (!$companyServiceParent) {
            $companyServiceParent = new CompanyAutoService();
            $companyServiceParent->setService($service);
            $companyServiceParent->setSpecialization($specialization);
            $em = $this->getDoctrine()->getManager();
            $em->persist($companyServiceParent);
            $em->flush();
            $companyServices[] = $companyServiceParent;
        }
        if ($service->getParent()) {
            $this->createCompanyServiceParent($companyServices, $companyServiceParent, $specialization);
        }
    }

    protected function setCompanyServiceParent($companyServices, $companyService)
    {
        foreach ($companyServices as $seachCompanyService) {
            if ($companyService->getService()->getParent() == $seachCompanyService->getService()) {
                $companyService->setParent($seachCompanyService);
                break;
            }
        }
    }
}
