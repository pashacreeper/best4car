<?php

namespace Sto\AdminBundle\Controller;


use Symfony\Component\HttpFoundation\Request,
    Symfony\Component\Translation\Translator,
    Symfony\Component\HttpFoundation\Responce,
    Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sto\CoreBundle\Entity\Dictionary,
    Sto\AdminBundle\Form\DictionaryType;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Dictionary controller.
 *
 * @Route("/dictionary")
 */
class DictionaryController extends Controller
{
    /**
     * Lists all Dictionary entities.
     *
     * @Route("/", name="dictionary")
     * @Template("StoAdminBundle:Dictionary:indexNew.html.twig")
     */
    public function indexAction()
    {
        return array();
    }

    public function checkAuthAjax()
    {
        if (true === $this->get('security.context')->isGranted('IS_AUTHENTICATED_ANONYMOUSLY')) {
            throw new AccessDeniedException();
            // return new Responce(401, 'Not Authorized.');
        }
    }

    /**
     * Lists all Dictionary entities.
     *
     * @Route("/change_field", name="dictionary_change_field")
     */
    public function changeFieldAction(Request $request)
    {
        // Ajax function
        $pk = $request->get('pk');
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('StoCoreBundle:Dictionary')->find($pk);

        if (!$entity) {
            return new Responce(500, 'Dictionary Not found.');
        }

        $newName = $request->get('value');
        $entity->setName($newName);
        $em->persist($entity);
        $em->flush();

       return new Responce(200);
    }

    /**
     * Deletes a Dictionary entity.
     *
     * @Route("/delete_ajax", name="dictionary_delete_ajax")
     * @Method("POST")
     */
    public function deleteAjaxAction(Request $request)
    {
        // check
        // if !user return new Responce(403, 'Dictionary Not found.');
        // class->checkAuth();
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('StoCoreBundle:Dictionary')->find($request->get('id'));

        if (!$entity) {
            return new Responce(500, 'Dictionary Not found.');
        }

        $em->remove($entity);
        $em->flush();

        return new Responce(200);
    }

    /**
     * Lists all Dictionary entities.
     *
     * @Route("/content", name="dictionary_content")
     * @Template("StoAdminBundle:Dictionary:content.html.twig")
     */
    public function contentAction(Request $request)
    {
        // $this->checkAuthAjax(); // check for user

        $filter_parent_id = $request->request->get('filter_parent');
        $filter_name = $request->request->get('filter_dict_name');

        $session = $this->get('session');
        if ($filter_parent_id)
            $session->set('filter_parent_id', $filter_parent_id);
        $session->set('filter_dict_name', $filter_name);

        $em = $this->getDoctrine()->getManager();

        $session = $this->get('session');
        $filter_parent_id = $session->get('filter_parent_id');
        $filter_name = $session->get('filter_dict_name');

        $query = $em->getRepository('StoCoreBundle:Dictionary')
            ->createQueryBuilder('entity');
        // apply Filters ---
        if (isset($filter_parent_id) && $filter_parent_id != -1 && isset($filter_name) && !empty($filter_name)) {

            $query->where(
                $query->expr()->andX(
                    $query->expr()->eq('entity.parentId', $filter_parent_id),
                    $query->expr()->like('entity.name', $query->expr()->literal('%' . $filter_name . '%'))
                    )
                );

        } else {
            // 1
            if ($filter_parent_id && $filter_parent_id != -1)
                $query->where('entity.parentId =:filter_parent_id')
                    ->setParameter('filter_parent_id', $filter_parent_id);
            else
                $session->set('filter_parent_id', '-1');
            // 2
            if (isset($filter_name) && !empty($filter_name)) {
                $query->where( $query->expr()->like('entity.name', $query->expr()->literal('%' . $filter_name . '%')) );
            } else
                $session->set('filter_dict_name', '');
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

        $parents = $em->getRepository('StoCoreBundle:Dictionary')
            ->createQueryBuilder('entity')
            ->where('entity.parentId is NULL')
            ->orderBy('entity.id')
            ->getQuery()
            ->getResult();

        return array(
            'pagination' => $pagination,
            'parents' => $parents,
        );
    }

    /**
     * Displays a form to create a new Dictionary entity.
     *
     * @Route("/new", name="dictionary_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Dictionary();
        $form   = $this->createForm(new DictionaryType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new Dictionary entity.
     *
     * @Route("/create", name="dictionary_create")
     * @Method("POST")
     * @Template("StoCoreBundle:Dictionary:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Dictionary();
        $form = $this->createForm(new DictionaryType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('dictionary'));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Dictionary entity.
     *
     * @Route("/{id}/edit", name="dictionary_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('StoCoreBundle:Dictionary')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException($this->get('translator')->trans('dict.errors.unable_2_find'));
        }

        $editForm = $this->createForm(new DictionaryType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Dictionary entity.
     *
     * @Route("/{id}/update", name="dictionary_update")
     * @Method("POST")
     * @Template("StoCoreBundle:Dictionary:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('StoCoreBundle:Dictionary')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException($this->get('translator')->trans('dict.errors.unable_2_find'));
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new DictionaryType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('dictionary'));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Dictionary entity.
     *
     * @Route("/{id}/delete", name="dictionary_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('StoCoreBundle:Dictionary')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException($this->get('translator')->trans('dict.errors.unable_2_find'));
        }

        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('dictionary'));
    }

    /**
     * Set Filter for a Dictionary lists.
     *
     * @Route("/filter", name="dictionary_filter")
     * @Method("POST")
     */
    public function filterAction(Request $request)
    {
        $filter_parent_id = $request->request->get('filter_parent');
        $filter_name = $request->request->get('filter_dict_name');

        $session = $this->get('session');
        if ($filter_parent_id)
            $session->set('filter_parent_id', $filter_parent_id);
        $session->set('filter_dict_name', $filter_name);

        return $this->redirect($this->generateUrl('dictionary'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }

    public function listByParentIdAction($parent_id)
    {
        $serializer = $this->container->get('serializer');
        $em = $this->getDoctrine()->getManager();
        $data = $em->getRepository('StoCoreBundle:Dictionary')
                ->createQueryBuilder('entity')
                ->where('entity.parentId =:filter_parent_id')
                ->setParameter('filter_parent_id', $parent_id)
                ->orderBy('entity.name')
                ->getQuery()
                ->getResult();

        return $data;

    }

    public function getCurrensiesAction()
    {
        $head_id = $this->container->getParameter('dictionary_currencies_id');

        return $this->listByParentIdAction($head_id);
    }

    public function getServicesListAction()
    {
        $head_id = $this->container->getParameter('dictionary_services_list_id');

        return $this->listByParentIdAction($head_id);
    }

    public function getAdditionalServicesListAction()
    {
        $head_id = $this->container->getParameter('dictionary_additional_services_id');

        return $this->listByParentIdAction($head_id);
    }

    public function getCompanyTypesAction()
    {
        $head_id = $this->container->getParameter('dictionary_company_types_id');

        return $this->listByParentIdAction($head_id);
    }

}
