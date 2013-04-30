<?php

namespace Sto\ContentBundle\Controller;

use Symfony\Component\HttpFoundation\Request,
    Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sto\UserBundle\Entity\User,
    Sto\ContentBundle\Form\RegistrationType;

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
        $form = $this->createForm(new RegistrationType(), $user);
        return [
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
        $form = $this->createForm(new RegistrationType(), $user);
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
    public function newCompanyAction()
    {
        return [
        ];
    }
}
