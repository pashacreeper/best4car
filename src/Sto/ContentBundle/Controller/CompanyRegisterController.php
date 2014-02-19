<?php
namespace Sto\ContentBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sto\ContentBundle\Form\Extension\ChoiceList\CompanyRegistrationStep;
use Sto\ContentBundle\Form\RegistrationType;
use Sto\ContentBundle\Form\Type\CompanyBaseType;
use Sto\ContentBundle\Form\Type\CompanyBusinessProfileType;
use Sto\ContentBundle\Form\Type\CompanyContactsType;
use Sto\ContentBundle\Form\Type\CompanyGalleryType;
use Sto\ContentBundle\Helper\CompanyServiceHelper;
use Sto\CoreBundle\Entity\Company;
use Sto\CoreBundle\Entity\CompanyAutoService;
use Sto\CoreBundle\Entity\CompanyManager;
use Sto\CoreBundle\Entity\CompanySpecialization;
use Sto\CoreBundle\Entity\CompanyWorkingTime;
use Sto\UserBundle\Entity\Group;
use Sto\UserBundle\Entity\RatingGroup;
use Sto\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Doctrine\ORM\PersistentCollection;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sto\CoreBundle\Entity\CompanyEmail;
use Sto\CoreBundle\Entity\CompanyPhone;

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
            $ratingGroup = $em->getRepository('StoUserBundle:RatingGroup')->find(RatingGroup::ENTHUSIAST);
            $user->setRatingGroup($ratingGroup);
            $group = $em->getRepository('StoUserBundle:Group')->find(Group::MANAGER);
            $user->setGroups([$group]);
            $user->setEnabled(true);

            $em->persist($user);
            $em->flush();

            $this->get('sto.notifications.email')->sendRegistrationEmail($user);
            $this->get('sto.user.authenticate')->authenticate($user);

            return $this->redirect($this->generateUrl('registration_company_base'));
        }

        return [
            'user' => $user,
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/new-company/base", name="registration_company_base")
     * @Route("/company/{id}/edit/base", name="company_edit_base")
     * @Template()
     * @Secure(roles="ROLE_USER")
     */
    public function baseAction(Request $request, $id = null)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $isCompanyNew = false;

        if (! $id || ! $company = $em->getRepository('StoCoreBundle:Company')->find($id)) {
            $company = new Company();
            $isCompanyNew = true;
        }

        if ($company->getCompanyManager()->count() > 0 || $user->hasRole('ROLE_ADMIN')) {
            $this->checkCompanyManager($company);
        }

        $form = $this->createForm(new CompanyBaseType(), $company);

        if ('POST' === $request->getMethod()) {
            $form->submit($request);

            if ($form->isValid()) {
                if ($isCompanyNew) {
                    $manager = new CompanyManager();
                    $manager->setUser($user);
                    $manager->setPhone($user->getPhoneNumber());
                    $manager->setCompany($company);

                    $company->addCompanyManager($manager);
                    $company->setRegistredFully(false);
                    $company->setRegistrationStep(CompanyRegistrationStep::BUSINESS);
                    $company->setVisible(false);
                }

                $em->persist($company);
                $em->flush();

                return $this->redirect($this->generateUrl('company_edit_business_profile', [
                    'id' => $company->getId()
                ]));
            }
        }

        return [
            'form' => $form->createView(),
            'company' => $company,
        ];
    }

    /**
     * @Route("/company/{id}/edit/business-profile/", name="company_edit_business_profile")
     * @Template()
     * @Secure(roles="ROLE_USER")
     */
    public function businessProfileAction(Request $request, Company $company)
    {
        if ($company->getSpecializations()->count() === 0) {
            $company->addSpecialization(new CompanySpecialization());
        }
        $this->checkCompanyManager($company);

        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(new CompanyBusinessProfileType(), $company);

        $additionalServiceTypes = $em->getRepository('StoCoreBundle:Dictionary\AdditionalService')->findAll();

        if ('POST' === $request->getMethod()) {
            $form->bind($request);

            $services = $request->get('services');

            if (empty($services) || (count($services) < $form->getData()->getSpecializations()->count())) {
                $form->get('specializations')->addError(new FormError('Необходимо выбрать услуги в рамках специализации'));
            }

            if ($form->isValid()) {
                $company->setRegistrationStep(CompanyRegistrationStep::CONTACTS);
                $em->persist($company);

                foreach ($company->getSpecializations() as $key => $item) {
                    if (isset($services[$key])) {
                        $itemServices = $services[$key];
                        foreach ($item->getServices() as $oldService) {
                            $serviceId = $oldService->getService()->getId();
                            if (($serviceKey = array_search($serviceId, $itemServices)) !== false) {
                                unset($itemServices[$serviceKey]);
                            } else {
                                $em->remove($oldService);
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
                        $companyServiceHelper = new CompanyServiceHelper($em);
                        foreach ($companyServices as $companyService) {
                            if ($companyService->getService()->getParent()) {
                                $companyServiceHelper->createCompanyServiceParent($companyServices, $companyService, $item);
                            }
                        }
                        $em->flush();
                        foreach ($companyServices as $companyService) {
                            if ($companyService->getService()->getParent()) {
                                $companyServiceHelper->setCompanyServiceParent($companyServices, $companyService);
                            }
                        }
                    }
                }

                $em->flush();

                return $this->redirect($this->generateUrl('company_edit_contacts', [
                    'id' => $company->getId()
                ]));
            }
        }

        return [
            'company' => $company,
            'form' => $form->createView(),
            'additionalServiceTypes' => $additionalServiceTypes
        ];
    }

    /**
     * @Route("/company/{id}/edit/contacts", name="company_edit_contacts")
     * @Template()
     * @Secure(roles="ROLE_USER")
     */
    public function contactsAction(Request $request, Company $company)
    {
        $this->checkCompanyManager($company);

        if ($company->getWorkingTime()->count() === 0) {
            $company->addWorkingTime(new CompanyWorkingTime());
        }
        if ($company->getEmails()->count() === 0) {
            $company->addEmail(new CompanyEmail());
        }
        if ($company->getPhones()->count() === 0) {
            $company->addPhone(new CompanyPhone());
        }

        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(new CompanyContactsType(),
            $company,
            ['em' => $em]
        );

        $form->handleRequest($request);

        if ($form->isValid()) {
            foreach ($company->getPhones() as $phone) {
                if ($phone->getCompany() === null) {
                    $phone->setCompany($company);
                }
            }

            foreach ($company->getCompanyManager() as $manager) {
                if ($manager->getCompany() === null) {
                    $manager->setCompany($company);
                }
            }

            foreach ($company->getEmails() as $email) {
                if ($email->getCompany() === null) {
                    $email->setCompany($company);
                }
            }

            if (!$company->isRegistredFully()) {
                $this->get('sto.notifications.email')->sendCompanyRegisterEmail($company);
            }

            $company->setRegistrationStep(CompanyRegistrationStep::GALLERY);
            $company->setRegistredFully(true);
            $company->setVisible(true);

            $em->persist($company);
            $em->flush();

            return $this->redirect($this->generateUrl('company_edit_gallery', [
                'id' => $company->getId()
            ]));
        }

        return [
            'form' => $form->createView(),
            'company' => $company
        ];
    }

    /**
     * @Route("/company/{id}/edit/gallery", name="company_edit_gallery")
     * @Template()
     * @Secure(roles="ROLE_USER")
     */
    public function galleryAction(Request $request, Company $company)
    {
        $this->checkCompanyManager($company);

        $form = $this->createForm(new CompanyGalleryType(), $company);
        $em = $this->getDoctrine()->getManager();

        if ('POST' === $request->getMethod()) {
            $form->bind($request);

            if ($form->isValid()) {
                $gallery = $company->getGallery();
                foreach ($gallery as $value) {
                    $value->setVisible(true);
                    $value->setCompany($company);
                }
                $company->setGallery($gallery);

                $company->setRegistrationStep(null);
                $em->persist($company);
                $em->flush();

                return $this->redirect($this->generateUrl('content_company_show', [
                    'id' => $company->getId()
                ]));
            }
        }

        return [
            'form' => $form->createView(),
            'company' => $company
        ];
    }

    /**
     * Check is user is one of company managers
     * @param  PersistentCollection $managers
     * @param  User                 $user
     * @return boolean
     */
    protected function checkCompanyManager(Company $company)
    {
        if ($this->get('security.context')->isGranted("EDIT", $company)) {
            return true;
        }

        throw new AccessDeniedException();
    }
}
