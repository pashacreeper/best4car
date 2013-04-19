<?php

namespace Sto\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request,
    Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sto\CoreBundle\Entity\Catalog;

/**
 * AutoCatalog controller.
 *
 * @Route("/catalog")
 */
class AutoCatalogController extends Controller
{
    private $levels = ['marks' => 'models', 'models' => 'items'];

    /**
     * Lists all AutoCatalog entities.
     *
     * @Route("/", name="admin_catalog")
     * @Route("/{level}/{parent}", name="admin_catalog_with_level")
     * @Template()
     */
    public function indexAction($level = 'marks', $parent = null)
    {
        if (!isset($this->levels[$level])) {
            throw $this->createNotFoundException('Page not found!');
        }

        $qb = $this->getDoctrine()->getManager()->createQueryBuilder();
        $query = $qb
            ->select('catalog')
            ->from('StoCoreBundle:Catalog\Base','catalog')
            ->where(
                ($parent == null) ? $qb->expr()->isNull('catalog.parentId') : $qb->expr()->eq('catalog.parentId', $qb->expr()->literal($parent))
            )
            ->orderBy('catalog.name')
            ->getQuery()
        ;

        $pagination = $this->get('knp_paginator')->paginate(
            $query,
            $this->get('request')->query->get('page', 1),
            $this->get('request')->query->get('numItemsPerPage', $this->container->getParameter('pagination_default_value'))
        );

        return [
            'catalog' => $pagination,
            'level' => $this->levels[$level]
        ];
    }

    /**
     * Finds and displays a AutoCatalog entity.
     *
     * @Route("items/{parent}", name="admin_catalog_items_show")
     * @Template()
     */
    public function showItemsAction($parent)
    {
        $em = $this->getDoctrine()->getManager();
        $items = $em->getRepository('StoCoreBundle:Catalog\ModelFull')->findByParentId($parent);

        return [
            'items' => $items,
        ];
    }

    /**
     * Deletes a Dictionary entity.
     *
     * @Route("/{id}/delete/{parent}/{type}", name="autocatalog_delete", defaults={"parent" = "NULL", "type" = "mark"})
     * @Method("GET")
     */
    public function deleteAction(Request $request, $id, $type, $parent)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('StoCoreBundle:Catalog\Base')->findOneById($id);

        if (!$entity) {
            throw $this->createNotFoundException($this->get('translator')->trans('dict.errors.unable_2_find'));
        }

        $em->remove($entity);
        $em->flush();

        return $this->generateRedirect($type, $parent);
    }
}
