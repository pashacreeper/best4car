<?php

namespace Sto\ContentBundle\Controller;

use Symfony\Component\HttpFoundation\Request,
    Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sto\UserBundle\Entity\User,
    Sto\CoreBundle\Entity\Company,
    Sto\ContentBundle\Form\RegistrationType,
    Sto\ContentBundle\Form\RegistrationCompanyType,
    Sto\ContentBundle\Form\CompanyType;
use Symfony\Component\Form\FormError;

/**
 * User controller.
 *
 * @Route("/user")
 */
class UserController extends Controller
{
    /**
     * Registration car owner
     *
     * @Route("/registration/car-owner", name="registration_car_owner")
     * @Template()
     */
    public function newCarOwnerAction()
    {
        $user = new User();
        $form = $this->createForm(new RegistrationType('Sto\UserBundle\Entity\User'), $user);
        return [
            'user' => $user,
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
        if ($request->get('_username') && $request->get('_password'))
        {
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository('StoUserBundle:User')->findOneBy(['username' => $request->get('_username')]);
            if (!$user){
                $errors = 'Username is incorrect!';
                $errorFlag = true;
            }
            else {
                $encoder = $this->container
                    ->get('security.encoder_factory')
                    ->getEncoder($user)
                ;
                if (!($user->getPassword()==$encoder->encodePassword($request->get('_password'), $user->getSalt()))){
                    $errors = 'Password is incorrect!';
                    $errorFlag = true;
                }
            }
        }
        else {
            $errors = 'No data in form!';
            $errorFlag = true;
        }

        if (!$errorFlag){
            return $this->redirect($this->generateUrl('add_company', ['id'=>$user->getId()]));
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

        $user  = new User();
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

        return array(
            'user' => $user,
            'form'   => $form->createView(),
        );
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

        /*$em = $this->getDoctrine()->getManager();
        $company = new Company;
        $cForm = $this->createForm(new CompanyType(), $company, ['em'=>$em = $this->getDoctrine()->getManager()]);*/

        return [
            'user' => $user,
            'form' => $form->createView(),
            /*'company' => $company,
            'cForm' => $cForm->createView()*/
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
            if ($another_user || $another_email){
                if ($another_user)
                    $form->get('username')->addError(new FormError('Пользователь с таким ником уже зарегистрирован!'));
                if ($another_email)
                    $form->get('email')->addError(new FormError('Пользователь с таким почтовым адресом уже зарегистрирован!'));

                return array(
                    'user' => $user,
                    'form'   => $form->createView(),
                );
            }

            $em->persist($user);
            $em->flush();


            return $this->redirect($this->generateUrl('add_company', ['id'=>$user->getId()]));
        }

        return array(
            'user' => $user,
            'form'   => $form->createView(),
        );
    }

    /**
     * Registration company
     *
     * @Route("/new-company/user-{id}", name="add_company")
     * @Template()
     */
    public function newCompanyAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('StoUserBundle:User')->find($id);

        $company = new Company();
        $company->addManager($user);
        $cForm = $this->createForm(new CompanyType(), $company, ['em'=>$em]);

        return [
            'company' => $company,
            'user' => $id,
            'cForm' => $cForm->createView()
        ];
    }

    /**
     * Creates a new User entity.
     *
     * @Route("/create-company/user-{id}", name="registration_company_create")
     * @Method("POST")
     * @Template("StoContentBundle:User:newCompany.html.twig")
     */
    public function createCompanyAction(Request $request, $id)
    {
        $company  = new Company();
        $form = $this->createForm(new CompanyType(), $company, ['em'=> $em = $this->getDoctrine()->getManager()]);
        /*print "<pre>";
        print_r($request);
        print "</pre>"; exit;*/

        $form->bind($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository('StoUserBundle:User')->find($id);
            $company->addManager($user);
            $company->setUpdatedAt(new \DateTime());
            $em->persist($company);
            $em->flush();

            $this->get('session')->getFlashBag()->add('notice', 'Your company was added. Login please.');

            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }

        return array(
            'company' => $company,
            'user' => $id,
            'cForm'   => $form->createView(),
        );
    }
}
