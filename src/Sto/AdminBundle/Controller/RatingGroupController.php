<?php

namespace Sto\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request,
    Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sto\UserBundle\Entity\RatingGroup,
    Sto\AdminBundle\Form\RatingGroupType;

/**
 * User controller.
 *
 * @Route("/rating-group")
 */
class RatingGroupController extends Controller
{
    /**
     * Lists all User entities.
     *
     * @Route("/", name="rating_groups")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository('StoUserBundle:RatingGroup')
            ->createQueryBuilder('rating_group')
            ->orderBy('rating_group.id')
            ->getQuery()
        ;

        $def_limit = $this->container->getParameter('pagination_default_value');

        $groups = $this->get('knp_paginator')->paginate(
            $query,
            $this->get('request')->query->get('page', 1),
            $this->get('request')->query->get('numItemsPerPage', $def_limit)
        );

        return [
            'entities' => $groups,
        ];
    }

    /**
     * Finds and displays a User entity.
     *
     * @Route("/{id}/show", name="rating_groups_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('StoUserBundle:RatingGroup')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Group entity.');
        }

        return array(
            'entity'      => $entity,

        );
    }

    /**
     * Displays a form to edit an existing User entity.
     *
     * @Route("/{id}/edit", name="rating_groups_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('StoUserBundle:RatingGroup')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Group entity.');
        }

        $editForm = $this->createForm(new RatingGroupType(), $entity);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        );
    }

    /**
     * Edits an existing User entity.
     *
     * @Route("/{id}/update", name="rating_groups_update")
     * @Method("POST")
     * @Template("StoUserBundle:User:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('StoUserBundle:RatingGroup')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Group entity.');
        }

        $editForm = $this->createForm(new RatingGroupType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('rating_groups'));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),

        );
    }

}
