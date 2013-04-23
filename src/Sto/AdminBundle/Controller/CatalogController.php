<?php

namespace Sto\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request,
    Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sto\CoreBundle\Entity\Catalog;
use Sto\AdminBundle\Form\CatalogType;

/**
 * AutoCatalog controller.
 *
 * @Route("/catalog")
 */
class CatalogController extends Controller
{
    private $levels = ['mark' => 'mark', 'model' => 'model', 'modification' => 'modification'];

    /**
     * Lists all AutoCatalog entities.
     *
     * @Route("/", name="admin_catalog")
     * @Route("/{level}/{parent}", name="admin_catalog_with_level")
     * @Template()
     */
    public function indexAction($level = 'mark', $parent = null)
    {
        if (!isset($this->levels[$level])) {
            throw $this->createNotFoundException('Page not found!');
        }

        $em = $this->getDoctrine()->getManager();
        if ($level !== 'modification') {
            $qb = $em->createQueryBuilder();
            $query = $qb
                ->select('catalog')
                ->from('StoCoreBundle:Catalog\Base','catalog')
                ->where(
                    ($parent == null) ? $qb->expr()->isNull('catalog.parentId') : $qb->expr()->eq('catalog.parentId', $qb->expr()->literal($parent))
                )
                ->orderBy('catalog.name')
                ->getQuery()
            ;
        } else {
            $query = $em->getRepository('StoCoreBundle:Catalog\Modification')
                ->createQueryBuilder('modification')
                ->where('modification.parentId = :parent')
                ->setParameter('parent', $parent)
                ->getQuery()
            ;
        }

        $pagination = $this->get('knp_paginator')->paginate(
            $query,
            $this->get('request')->query->get('page', 1),
            $this->get('request')->query->get('numItemsPerPage', $this->container->getParameter('pagination_default_value'))
        );

        return [
            'catalog' => $pagination,
            'parent' => $parent,
            'level' => $this->levels[$level]
        ];
    }

    /**
     * @Route("/{level}/{id}/delete", name="auto_catalog_delete")
     * @Method("POST")
     */
    public function deleteAction($level, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('StoCoreBundle:Catalog\Base')->findOneById($id);

        if (!$entity) {
            throw $this->createNotFoundException($this->get('translator')->trans('dict.errors.unable_2_find'));
        }

        $parent = $entity->getParentId();
        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('admin_catalog_with_level', ['level' => $level, 'parent' => $parent]));
    }

    /**
     * @Route("/{level}/{id}/edit", name="admin_catalog_edit")
     * @Template()
     */
    public function editAction($level, $id)
    {
        $em = $this->getDoctrine()->getManager();
        switch ($level) {
            case 'mark':
                $entity = $em->getRepository('StoCoreBundle:Catalog\Mark')->findOneById($id);
                $editForm = $this->createForm(new CatalogType\Mark, $entity);
                break;
            case 'model':
                $entity = $em->getRepository('StoCoreBundle:Catalog\Model')->findOneById($id);
                $editForm = $this->createForm(new CatalogType\Model, $entity);
                break;
            case 'modification':
                $entity = $em->getRepository('StoCoreBundle:Catalog\Modification')->findOneById($id);
                $editForm = $this->createForm(new CatalogType\Modification, $entity);
                break;
            default:
                throw $this->createNotFoundException($this->get('translator')->trans('dict.errors.unable_2_find'));
                break;
        }

        if (!$entity) {
            throw $this->createNotFoundException($this->get('translator')->trans('dict.errors.unable_2_find'));
        }

        return [
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'level' => $level,
        ];
    }

    /**
     * @Route("/{level}/{id}/update", name="admin_catalog_update")
     * @Method("POST")
     * @Template("StoAdminBundle:Catalog:edit.html.twig")
     */
    public function updateAction(Request $request, $level, $id)
    {
        $em = $this->getDoctrine()->getManager();
        switch ($level) {
            case 'mark':
                $entity = $em->getRepository('StoCoreBundle:Catalog\Mark')->findOneById($id);
                $editForm = $this->createForm(new CatalogType\Mark, $entity);
                break;
            case 'model':
                $entity = $em->getRepository('StoCoreBundle:Catalog\Model')->findOneById($id);
                $editForm = $this->createForm(new CatalogType\Model, $entity);
                break;
            case 'modification':
                $entity = $em->getRepository('StoCoreBundle:Catalog\Modification')->findOneById($id);
                $editForm = $this->createForm(new CatalogType\Modification, $entity);
                break;
            default:
                throw $this->createNotFoundException($this->get('translator')->trans('dict.errors.unable_2_find'));
                break;
        }

        if (!$entity) {
            throw $this->createNotFoundException($this->get('translator')->trans('dict.errors.unable_2_find'));
        }

        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_catalog_with_level', ['level' => $level, 'parent' => $entity->getParentId()]));
        }

        return [
            'entity' => $entity,
            'dictionary' => $dictionary,
            'edit_form' => $editForm->createView(),
        ];
    }

    /**
     * @Route("/{level}/notparent/new", name="admin_catalog_new")
     * @Route("/{level}/{parent}/new", name="admin_catalog_with_parent_new")
     * @Template()
     */
    public function newAction($level, $parent = null)
    {
        if ($parent) {
            $parent = $this->getDoctrine()->getManager()->getRepository('StoCoreBundle:Catalog\Base')->findOneById($parent);
        }

        switch ($level) {
            case 'mark':
                $form = $this->createForm(new CatalogType\Mark, (new Catalog\Mark)->setParent($parent));
                break;
            case 'model':
                $form = $this->createForm(new CatalogType\Model, (new Catalog\Model)->setParent($parent));
                break;
            case 'modification':
                $form = $this->createForm(new CatalogType\Modification, (new Catalog\Modification)->setParent($parent));
                break;
            default:
                throw $this->createNotFoundException($this->get('translator')->trans('dict.errors.unable_2_find'));
                break;
        }

        return [
            'level' => $level,
            'parent' => $parent,
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/{level}/notparent/create", name="admin_catalog_create")
     * @Route("/{level}/{parent}/create", name="admin_catalog_with_parent_create")
     * @Method("POST")
     * @Template("StoCoreBundle:Catalog:new.html.twig")
     */
    public function createAction(Request $request, $level, $parent = null)
    {
        $em = $this->getDoctrine()->getManager();
        switch ($level) {
            case 'mark':
                $entity = new Catalog\Mark;
                $form = $this->createForm(new CatalogType\Mark, $entity);
                break;
            case 'model':
                $entity = new Catalog\Model;
                $form = $this->createForm(new CatalogType\Model, $entity);
                break;
            case 'modification':
                $entity = new Catalog\Modification;
                $form = $this->createForm(new CatalogType\Modification, $entity);
                break;
            default:
                throw $this->createNotFoundException($this->get('translator')->trans('dict.errors.unable_2_find'));
                break;
        }
        $form->bind($request);

        if ($form->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_catalog'));
        }

        return [
            'level' => $level,
            'parent' => $parent,
            'form' => $form->createView(),
        ];
    }
}
