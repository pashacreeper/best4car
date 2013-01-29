<?php

namespace Sto\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sto\CoreBundle\Entity\City;
use Sto\AdminBundle\Form\CityType;

/**
 * City controller.
 *
 * @Route("/admin/city")
 */
class CityController extends Controller
{
    /**
     * Lists all City entities.
     *
     * @Route("/", name="city")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $session = $this->get('session');
        $filter_parent_id = $session->get('filter_country_id');
        $filter_name = $session->get('filter_city_name');


        $query = $em->getRepository('StoCoreBundle:City')
            ->createQueryBuilder('entity');


        // apply Filters ---
        if (isset($filter_parent_id) && $filter_parent_id != -1 && isset($filter_name) && !empty($filter_name)) {

            $query->where(
                $query->expr()->andX(
                    $query->expr()->eq('entity.countryId', $filter_parent_id),
                    $query->expr()->like('entity.name', $query->expr()->literal('%' . $filter_name . '%'))
                    )
                );

        } else {
            // 1
            if ($filter_parent_id && $filter_parent_id != -1)
                $query->where('entity.countryId =:filter_parent_id')
                    ->setParameter('filter_parent_id', $filter_parent_id);
            else
                $session->set('filter_country_id', '-1');
            // 2
            if (isset($filter_name) && !empty($filter_name)){
                $query->where( $query->expr()->like('entity.name', $query->expr()->literal('%' . $filter_name . '%')) );
            } else
                $session->set('filter_city_name', '');
        }
        // EOF filters

        $query->orderBy('entity.id')
            ->getQuery();


        $def_limit = $this->container->getParameter('pagination_default_value');

        $pagination = $this->get('knp_paginator')->paginate(
            $query,
            $this->get('request')->query->get('page', 1),
            $this->get('request')->query->get('numItemsPerPage', $def_limit)
        );

        $parents = $em->getRepository('StoCoreBundle:Country')
            ->createQueryBuilder('entity')
            ->orderBy('entity.name')
            ->getQuery()
            ->getResult();

        return array(
            'pagination' => $pagination,
            'parents' => $parents,

        );

    }

    /**
     * Displays a form to create a new City entity.
     *
     * @Route("/new", name="city_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new City();
        $form   = $this->createForm(new CityType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new City entity.
     *
     * @Route("/create", name="city_create")
     * @Method("POST")
     * @Template("StoCoreBundle:City:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new City();
        $form = $this->createForm(new CityType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('city'));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing City entity.
     *
     * @Route("/{id}/edit", name="city_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('StoCoreBundle:City')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException($this->get('translator')->trans('city.errors.unable_2_find'));
        }

        $editForm = $this->createForm(new CityType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing City entity.
     *
     * @Route("/{id}/update", name="city_update")
     * @Method("POST")
     * @Template("StoCoreBundle:City:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('StoCoreBundle:City')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException($this->get('translator')->trans('city.errors.unable_2_find'));
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new CityType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('city'));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a City entity.
     *
     * @Route("/{id}/delete", name="city_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('StoCoreBundle:City')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException($this->get('translator')->trans('city.errors.unable_2_find'));
        }

        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('city'));
    }

    /**
     * Set Filter for a Dictionary lists.
     *
     * @Route("/filter", name="city_filter")
     * @Method("POST")
     */
    public function filterAction(Request $request)
    {
        $filter_parent_id = $request->request->get('filter_parent');
        $filter_name = $request->request->get('filter_city_name');

        $session = $this->get('session');
        if ($filter_parent_id)
            $session->set('filter_country_id', $filter_parent_id);
        $session->set('filter_city_name', $filter_name);

        return $this->redirect($this->generateUrl('city'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
