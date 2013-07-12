<?php

namespace Sto\ContentBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sto\ContentBundle\Controller\ChoiceCityController as MainController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sto\UserBundle\Entity\User;
use Sto\CoreBundle\Entity\Company;
use Sto\ContentBundle\Form\RegistrationType;
use Sto\ContentBundle\Form\CompanyType;
use Sto\ContentBundle\Form\UserPersonalType;
use Symfony\Component\Form\FormError;
use Sto\CoreBundle\Entity\CompanyManager;
use Sto\ContentBundle\Form\AdditionalUserType;
use Sto\ContentBundle\Form\PhotoUserType;
use Sto\UserBundle\Entity\UserGallery,
    Sto\ContentBundle\Form\UserGalleryType;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * User controller.
 *
 * @Route("/user")
 */
class UserController extends MainController
{
    /**
     * User Profile
     *
     * @Route("/profile/{id}", name="user_profile")
     * @Template()
     */
    public function showUserAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('StoUserBundle:User')->findOneById($id);

        return [
            'user' => $user
        ];
    }

    /**
     * Registration car owner
     *
     * @Route("/registration/car-owner", name="registration_car_owner")
     * @Template()
     */
    public function newCarOwnerAction()
    {
        $form = $this->createForm(new RegistrationType('Sto\UserBundle\Entity\User'), new User);

        return [
            'form' => $form->createView()
        ];
    }

    /**
     * Registration company with owner
     *
     * @Route("/check_owner", name="content_register_company_with_owner")
     * @Method("POST")
     * @Template("StoContentBundle:User:newCompanyOwner.html.twig")
     */
    public function checkOwnerAction(Request $request)
    {
        $errorFlag = false;
        if ($request->get('_username') && $request->get('_password')) {
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository('StoUserBundle:User')->findOneBy(['username' => $request->get('_username')]);
            if (!$user) {
                $errors = "login.alerts.wrong_pass";
                $errorFlag = true;
            } else {
                $encoder = $this->container
                    ->get('security.encoder_factory')
                    ->getEncoder($user)
                ;
                if (!($user->getPassword()==$encoder->encodePassword($request->get('_password'), $user->getSalt()))) {
                    $errors = "login.alerts.wrong_pass";
                    $errorFlag = true;
                }
            }
        } else {
            $errors = "login.alerts.wrong_pass";
            $errorFlag = true;
        }

        if (!$errorFlag) {
            $this->authenticateUser($user);

            return $this->redirect($this->generateUrl('add_company'));
        }

        $user = new User();
        $form = $this->createForm(new RegistrationType('Sto\UserBundle\Entity\User'), $user);

        return [
            'last_username' => $request->get('_username'),
            'errors' => $errors,
            'errorFlag' => $errorFlag,
            'user' => $user,
            'form' => $form->createView()
        ];
    }

    /**
     * Creates a new User entity.
     *
     * @Route("/create", name="registration_car_owner_create")
     * @Method("POST")
     * @Template("StoContentBundle:User:newCarOwner.html.twig")
     */
    public function createCarOwnerAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(new RegistrationType('Sto\UserBundle\Entity\User'), $user);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $ratingGroup = $em->getRepository('StoUserBundle:RatingGroup')->find(1);
            $user->setRatingGroup($ratingGroup);
            $em->persist($user);
            $em->flush();

            return $this->redirect($this->generateUrl('content_companies'));
        }

        return [
            'user' => $user,
            'form' => $form->createView(),
        ];
    }

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
                if ($another_user)
                    $form->get('username')->addError(new FormError('Пользователь с таким ником уже зарегистрирован!'));
                if ($another_email)
                    $form->get('email')->addError(new FormError('Пользователь с таким почтовым адресом уже зарегистрирован!'));

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
     * Registration company
     *
     * @Route("/new-company/", name="add_company")
     * @Template()
     */
    public function newCompanyAction()
    {
        $em = $this->getDoctrine()->getManager();

        if (!$this->getUser())
            return new Responce(404, 'User Not found.');

        $user = $this->getUser();

        $company = new Company();
        $manager = new CompanyManager();
        $manager->setUser($user);
        $manager->setPhone($user->getPhoneNumber());
        $manager->setCompany($company);

        $company->addCompanyManager($manager);
        $cForm = $this->createForm(new CompanyType(), $company, ['em' => $em]);

        return [
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

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $company->setUpdatedAt(new \DateTime());

            $managers = $company->getCompanyManager();
            foreach ($managers as $value) {
                $value->setCompany($company);
            }
            $company->setCompanyManager($managers);

            //$user = $em->getRepository('StoUserBundle:User')->find($id);
            $user = $this->getUser();

            $gallery = $company->getGallery();
            foreach ($gallery as $value) {
                $value->setCompany($company);
            }
            $company->setGallery($gallery);

            $em->persist($company);
            $em->flush();

            $this->get('session')->getFlashBag()->add('notice', 'Your company was added. Login please.');

            return $this->redirect($this->generateUrl('content_company_show', ['id'=>$company->getId()]));
        }

        return [
            'company' => $company,
            'user' => $user->getId(),
            'cForm' => $form->createView(),
        ];
    }

    /**
     * Registration company
     *
     * @Route("/check-vk-user", name="content_check_vk_user")
     * @Template()
     */
    public function checkVkUserAction(Request $request)
    {
        $hash = $request->get('hash');
        $uid = $request->get('uid');
        $first_name = $request->get('first_name');
        $last_name = $request->get('last_name');
        if ($hash == md5($this->container->getParameter('vk_client_id').$uid.$this->container->getParameter('vk_client_secret'))) {
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository('StoUserBundle:User')->findOneBy(['linkVK' => $uid]);
            if ($user) {
                exit('YES');
            }
        }
        exit('NO');

        return [];
    }

    /**
     * Generate Form
     *
     * @Route("/generate-personal", name="content_get_personal_form")
     * @Template("StoContentBundle:User:personal_form.html.twig")
     */
    public function generatePersonalFormAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('StoUserBundle:User')->find($this->getUser()->getId());

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $form = $this->createForm(new UserPersonalType, $entity);

        return [
            'form' => $form->createView()
        ];
    }

    /**
     * After VK accounting
     *
     * @Route("/vk-accounting", name="user-vk-accounting")
     * @Template("StoContentBundle:User:vk_additional.html.twig")
     * @Method("GET")
     */
    public function vkAdditionalAction()
    {
        if (!$this->getUser()->isUsingEmail()) {
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository('StoUserBundle:User')->find($this->getUser()->getId());

            $form = $this->createForm(new AdditionalUserType(), $user);

            return [
                'form' => $form->createView(),
                'user' => $user
            ];
        }

        return $this->redirect($this->generateUrl('fos_user_profile_show'));
    }

    /**
     * After VK accounting
     *
     * @Route("/vk-accounting/save", name="user_vk_account_save")
     * @Template("StoContentBundle:User:vk_additional.html.twig")
     * @Method("POST")
     */
    public function saveVkAdditionalAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('StoUserBundle:User')->find($this->getUser()->getId());

        $form = $this->createForm(new AdditionalUserType(), $user);

        $form->bind($request);

        if ($form->isValid()) {
            $user->setUsingEmail(true);
            $password = $user->getPassword();
            $encoder = $this->container
                ->get('security.encoder_factory')
                ->getEncoder($user)
            ;
            $user->setPassword($encoder->encodePassword($password, $user->getSalt()));
            $em->persist($user);
            $em->flush();

            return $this->redirect($this->generateUrl('fos_user_profile_show'));
        }

        return [
            'form' => $form->createView(),
            'user' => $user
        ];
    }

    /**
     * Edit Avatar
     *
     * @Route("/avatar/edit", name="profile_edit_user_avatar")
     * @Template()
     * @Method("GET")
     */
    public function editAvatarAction()
    {

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('StoUserBundle:User')->findOneById($this->getUser()->getId());

        $form = $this->createForm(new PhotoUserType(), $user);

        return [
            'form' => $form->createView(),
            'user' => $user,
        ];
    }

    /**
     * Edit Avatar
     *
     * @Route("/avatar/update", name="profile_update_user_avatar")
     * @Template("StoContentBundle:User:editAvatar.html.twig")
     * @Method("POST")
     */
    public function updateAvatarAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('StoUserBundle:User')->findOneById($this->getUser()->getId());

        $form = $this->createForm(new PhotoUserType(), $user);

        $form->bind($request);

        if ($form->isValid()) {
            $em->persist($user);
            $em->flush();

            return $this->redirect($this->generateUrl('fos_user_profile_show'));
        }

        return $this->redirect($this->generateUrl('fos_user_profile_show'));
    }

    /**
     * avatar remove
     *
     * @Route("/avatar/remove/", name="profile_user_avatar_remove")
     * @Method("GET")
     */
    public function removeAvatarAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('StoUserBundle:User')->findOneById($this->getUser()->getId());

        unlink($user->getAvatar()->getRealpath());
        $user->setAvatarUrl(null);

        $em->persist($user);
        $em->flush();

        return $this->redirect($this->generateUrl('fos_user_profile_show'));
    }

    /**
     * gallery add
     *
     * @Route("/gallery/show/{id}", name="profile_user_gallery_show", defaults={"id" = 0})
     * @Template("StoContentBundle:User:show_gallery.html.twig")
     * @Method("GET")
     */
    public function showGalleryAction($id = 0)
    {
        $em = $this->getDoctrine()->getManager();
        if ($id == 0 && $this->getUser()) {
            $id = $this->getUser()->getId();
        } elseif ($id == 0 && $this->getUser()==null) {
            return new Responce(404, 'User Not found.');
        }

        $gallery = $em->getRepository('StoUserBundle:UserGallery')->findBy(['userId'=>$id]);

        return [
            'gallery' => $gallery,
        ];
    }

    /**
     * gallery remove
     *
     * @Route("/gallery/remove/{id}", name="profile_user_gallery_remove")
     * @Method("GET")
     */
    public function removeGalleryAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $gallery = $em->getRepository('StoUserBundle:UserGallery')->findOneById($id);

        if (!$gallery) {
            return new Responce(404, 'Image Not found.');
        }

        if ($gallery->getUser() != $this->getUser())
            return new Responce(403, 'It\'s not your image!');

        $em->remove($gallery);
        $em->flush();

        return $this->redirect($this->generateUrl('fos_user_profile_show'));
    }

    /**
     * gallery add
     *
     * @Route("/gallery/add", name="profile_user_gallery_add")
     * @Template("StoContentBundle:User:add_gallery.html.twig")
     * @Method("GET")
     */
    public function addGalleryAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('StoUserBundle:User')->findOneById($this->getUser()->getId());

        $gallery = new UserGallery($user);
        $form = $this->createForm(new UserGalleryType(), $gallery);

        return [
            'form' => $form->createView(),
            'user' => $user,
        ];
    }

    /**
     *
     * @Route("/gallery/upload", name="profile_user_gallery_upload")
     * @Template("StoContentBundle:User:add_gallery.html.twig")
     * @Method("POST")
     */
    public function uploadGalleryAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('StoUserBundle:User')->findOneById($this->getUser()->getId());

        $gallery = new UserGallery($user);
        $form = $this->createForm(new UserGalleryType(), $gallery);

        $form->bind($request);

        if ($form->isValid()) {
            $em->persist($gallery);
            $em->flush();

            return $this->redirect($this->generateUrl('fos_user_profile_show'));
        }

        return $this->redirect($this->generateUrl('fos_user_profile_show'));
    }

    private function authenticateUser(UserInterface $user)
    {
        $providerKey = 'main'; // your firewall name
        $token = new UsernamePasswordToken($user, null, $providerKey, $user->getRoles());

        $this->container->get('security.context')->setToken($token);
    }
}
