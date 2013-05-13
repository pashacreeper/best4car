<?php

namespace Sto\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sto\UserBundle\Entity\User;
use Sto\AdminBundle\Form\UserType;

/**
 * User controller.
 *
 * @Route("/user")
 */
class UserController extends Controller
{
    /**
     * Lists all User entities.
     *
     * @Route("/", name="admin_user")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository('StoUserBundle:User')
            ->createQueryBuilder('entity')
            ->orderBy('entity.id')
            ->getQuery();

        $def_limit = $this->container->getParameter('pagination_default_value');

        $pagination = $this->get('knp_paginator')->paginate(
            $query,
            $this->get('request')->query->get('page', 1),
            $this->get('request')->query->get('numItemsPerPage', $def_limit)
        );

        return [
            'pagination' => $pagination,
        ];
    }

    /**
     * Finds and displays a User entity.
     *
     * @Route("/{id}/show", name="admin_user_show")
     * @Template()
     * @ParamConverter("user", class="StoUserBundle:User")
     */
    public function showAction($user)
    {
        return [
            'entity' => $user,
        ];
    }

    /**
     * Displays a form to create a new User entity.
     *
     * @Route("/new", name="admin_user_new")
     * @Template()
     */
    public function newAction()
    {
        $form   = $this->createForm(new UserType, new User);

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * Creates a new User entity.
     *
     * @Route("/create", name="admin_user_create")
     * @Method("POST")
     * @Template("StoUserBundle:User:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new User;
        $form = $this->createForm(new UserType, $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_user'));
        }

        return [
            'entity' => $entity,
            'form'   => $form->createView(),
        ];
    }

    /**
     * Displays a form to edit an existing User entity.
     *
     * @Route("/{id}/edit", name="admin_user_edit")
     * @Method("GET")
     * @Template()
     * @ParamConverter("user", class="StoUserBundle:User")
     */
    public function editAction($user)
    {
        $editForm = $this->createForm(new UserType, $user);

        return [
            'entity'    => $user,
            'edit_form' => $editForm->createView(),
        ];
    }

    /**
     * Edits an existing User entity.
     *
     * @Route("/{id}/update", name="admin_user_update")
     * @Method("POST")
     * @Template("StoAdminBundle:User:edit.html.twig")
     * @ParamConverter("user", class="StoUserBundle:User")
     */
    public function updateAction(Request $request, $user)
    {
        $editForm = $this->createForm(new UserType, $user);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_user'));
        }

        return [
            'entity'    => $user,
            'edit_form' => $editForm->createView(),
        ];
    }

    /**
     * Deletes a User entity.
     *
     * @Route("/{id}/delete", name="admin_user_delete")
     * @Method("POST")
     * @ParamConverter("user", class="StoUserBundle:User")
     */
    public function deleteAction(Request $request, $user)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();

        return $this->redirect($this->generateUrl('admin_user'));
    }
}
