<?php

namespace Sto\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request,
    Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sto\CoreBundle\Entity\AutoCatalog,
    Sto\CoreBundle\Entity\AutoCatalogItem,
    Sto\CoreBundle\Entity\AutoCatalogCar,
    Sto\AdminBundle\Form\AutoCatalogType,
    Sto\AdminBundle\Form\AutoCatalogItemType;

/**
 * AutoCatalog controller.
 *
 * @Route("/autocatalog")
 */
class AutoCatalogController extends Controller
{
    /**
     * Lists all AutoCatalog entities.
     *
     * @Route("/", name="admin_autocatalog")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('StoCoreBundle:AutoCatalog')->findByParentId(NULL);

        return [
            'entities' => $entities,
        ];
    }

    /**
     * Finds and displays a AutoCatalog entity.
     *
     * @Route("/{id}/show-model", name="admin_autocatalog_show_model")
     * @Template()
     */
    public function showModelAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('StoCoreBundle:AutoCatalog')->findByParentId($id);

        if (!$entities) {
            throw $this->createNotFoundException('Unable to find AutoCatalog entity.');
        }

        return [
            'entities' => $entities,
        ];
    }

    /**
     * Finds and displays a AutoCatalog entity.
     *
     * @Route("/{id}/show", name="admin_autocatalog_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('StoCoreBundle:AutoCatalog')->findByParentId($id);

        if (!$entities) {
            throw $this->createNotFoundException('Unable to find AutoCatalog entity.');
        }

        return [
            'entities' => $entities,
        ];
    }

    /**
     * Displays a form to create a new Dictionary entity.
     *
     * @Route("/new", name="autocatalog_new")
     * @Template()
     */
    public function newAction()
    {
        return [
            'form' => $this->createForm(new AutoCatalogType, new AutoCatalogCar)->createView(),
        ];
    }

    /**
     * Creates a new Dictionary entity.
     *
     * @Route("/create", name="autocatalog_create")
     * @Method("POST")
     * @Template("StoAdminBundle:Autocatalog:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new AutoCatalogCar;
        $form = $this->createForm(new AutoCatalogType, $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_autocatalog'));
        }

        return [
            'entity' => $entity,
            'form' => $form->createView(),
        ];
    }

    /**
     * Displays a form to edit an existing Dictionary entity.
     *
     * @Route("/{id}/edit", name="autocatalog_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('StoCoreBundle:AutoCatalog')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException($this->get('translator')->trans('dict.errors.unable_2_find'));
        }
        if ($entity instanceof AutoCatalogItem)
            $editForm = $this->createForm(new AutoCatalogItemType, $entity);
        else
            $editForm = $this->createForm(new AutoCatalogType, $entity);

        return [
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
        ];
    }

    /**
     * Edits an existing Dictionary entity.
     *
     * @Route("/{id}/update", name="autocatalog_update")
     * @Method("POST")
     * @Template("StoAdminBundle:AutoCatalog:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('StoCoreBundle:AutoCatalog')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException($this->get('translator')->trans('dict.errors.unable_2_find'));
        }

        $editForm = $this->createForm(new AutoCatalogType, $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_autocatalog'));
        }

        return [
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
        ];
    }

    /**
     * Deletes a Dictionary entity.
     *
     * @Route("/{id}/delete", name="autocatalog_delete")
     * @Method("GET")
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('StoCoreBundle:AutoCatalog')->findOneById($id);

        if (!$entity) {
            throw $this->createNotFoundException($this->get('translator')->trans('dict.errors.unable_2_find'));
        }

        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('admin_autocatalog'));
    }
}
