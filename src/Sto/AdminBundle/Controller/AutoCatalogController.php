<?php

namespace Sto\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request,
    Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sto\CoreBundle\Entity\AutoCatalog,
    Sto\CoreBundle\Entity\AutoCatalogCar,
    Sto\CoreBundle\Entity\AutoCatalogModel,
    Sto\CoreBundle\Entity\AutoCatalogBody,
    Sto\CoreBundle\Entity\AutoCatalogItem;

use Sto\AdminBundle\Form\AutoCatalogType,
    Sto\AdminBundle\Form\AutoCatalogModelType,
    Sto\AdminBundle\Form\AutoCatalogBodyType,
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

        /*if (!$entities) {
            throw $this->createNotFoundException('Unable to find AutoCatalog entity.');
        }*/

        return [
            'entities' => $entities,
            'parent' => $id,
        ];
    }

    /**
     * Finds and displays a AutoCatalog entity.
     *
     * @Route("/{id}/show-body", name="admin_autocatalog_show_body")
     * @Template()
     */
    public function showBodyAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('StoCoreBundle:AutoCatalog')->findByParentId($id);

        /*if (!$entities) {
            throw $this->createNotFoundException('Unable to find AutoCatalog entity.');
        }*/

        return [
            'entities' => $entities,
            'parent' => $id,
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

        return [
            'entities' => $entities,
            'parent' => $id,
        ];
    }

    /**
     * Displays a form to create a new Dictionary entity.
     *
     * @Route("/new/{parent}/{type}", name="autocatalog_new", defaults={"parent" = "NULL", "type" = "mark"})
     * @Template()
     */
    public function newAction($parent, $type='mark')
    {

        $em = $this->getDoctrine()->getManager();
        $oParent = $em->getRepository('StoCoreBundle:AutoCatalog')->find($parent);
        if ($type=="mark") {
            $entity  = new AutoCatalogCar;
            $form = $this->createForm(new AutoCatalogType, $entity);
        } elseif ($type=="model") {
            $entity  = new AutoCatalogModel;
            $entity->setParent($oParent);
            $form = $this->createForm(new AutoCatalogModelType, $entity);
        } elseif ($type=="body") {
            $entity  = new AutoCatalogBody;
            $entity->setParent($oParent);
            $form = $this->createForm(new AutoCatalogBodyType, $entity);
        } elseif ($type=="item") {
            $entity  = new AutoCatalogItem;
            $entity->setParent($oParent);
            $form = $this->createForm(new AutoCatalogItemType, $entity);
        }

        return [
            'form' => $form->createView(),
            'type' => $type,
            'parent' => $parent,
        ];
    }

    /**
     * Creates a new Dictionary entity.
     *
     * @Route("/create/{parent}/{type}", name="autocatalog_create", defaults={"type" = "mark"})
     * @Method("POST")
     * @Template("StoAdminBundle:AutoCatalog:new.html.twig")
     */
    public function createAction(Request $request, $parent, $type="mark")
    {
        $em = $this->getDoctrine()->getManager();
        $oParent = $em->getRepository('StoCoreBundle:AutoCatalog')->find($parent);
        if ($type=="mark") {
            $entity  = new AutoCatalogCar;
            $form = $this->createForm(new AutoCatalogType, $entity);
        } elseif ($type=="model") {
            $entity  = new AutoCatalogModel;
            $entity->setParent($oParent);
            $form = $this->createForm(new AutoCatalogModelType, $entity);
        } elseif ($type=="body") {
            $entity  = new AutoCatalogBody;
            $entity->setParent($oParent);
            $form = $this->createForm(new AutoCatalogBodyType, $entity);
        } elseif ($type=="item") {
            $entity  = new AutoCatalogItem;
            $entity->setParent($oParent);
            $form = $this->createForm(new AutoCatalogItemType, $entity);
        }
        //$form = $this->createForm(new AutoCatalogType, $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->generateRedirect($type, $parent);
        }

        return [
            'entity' => $entity,
            'form' => $form->createView(),
            'type' => $type,
            'parent' => $parent,
        ];
    }

    /**
     * Displays a form to edit an existing Dictionary entity.
     *
     * @Route("/{id}/edit/{parent}/{type}", name="autocatalog_edit", defaults={"parent" = "NULL", "type" = "mark"})
     * @Template()
     */
    public function editAction($id, $parent, $type)
    {
        $em = $this->getDoctrine()->getManager();
        if ($type=="mark")
            $entity = $em->getRepository('StoCoreBundle:AutoCatalogCar')->find($id);
        elseif($type=="model")
            $entity = $em->getRepository('StoCoreBundle:AutoCatalogModel')->find($id);
        elseif($type=="body")
            $entity = $em->getRepository('StoCoreBundle:AutoCatalogBody')->find($id);
        elseif ($type=="item")
            $entity = $em->getRepository('StoCoreBundle:AutoCatalogItem')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException($this->get('translator')->trans('dict.errors.unable_2_find'));
        }
        if ($entity instanceof AutoCatalogItem)
            $editForm = $this->createForm(new AutoCatalogItemType, $entity);
        else if ($entity instanceof AutoCatalogBody)
            $editForm = $this->createForm(new AutoCatalogBodyType, $entity);
        else if ($entity instanceof AutoCatalogModel)
            $editForm = $this->createForm(new AutoCatalogModelType, $entity);
        else
            $editForm = $this->createForm(new AutoCatalogType, $entity);

        return [
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'type' => $type,
            'parent' => $parent,
        ];
    }

    /**
     * Edits an existing Dictionary entity.
     *
     * @Route("/{id}/update/{parent}/{type}", name="autocatalog_update", defaults={"parent" = "NULL", "type" = "mark"})
     * @Method("POST")
     * @Template("StoAdminBundle:AutoCatalog:edit.html.twig")
     */
    public function updateAction(Request $request, $id, $parent, $type)
    {
        $em = $this->getDoctrine()->getManager();
        if ($type=="mark")
            $entity = $em->getRepository('StoCoreBundle:AutoCatalogCar')->find($id);
        elseif($type=="model")
            $entity = $em->getRepository('StoCoreBundle:AutoCatalogModel')->find($id);
        elseif($type=="body")
            $entity = $em->getRepository('StoCoreBundle:AutoCatalogBody')->find($id);
        elseif ($type=="item")
            $entity = $em->getRepository('StoCoreBundle:AutoCatalogItem')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException($this->get('translator')->trans('dict.errors.unable_2_find'));
        }

        if ($entity instanceof AutoCatalogItem)
            $editForm = $this->createForm(new AutoCatalogItemType, $entity);
        else if ($entity instanceof AutoCatalogBody)
            $editForm = $this->createForm(new AutoCatalogBodyType, $entity);
        else if ($entity instanceof AutoCatalogModel)
            $editForm = $this->createForm(new AutoCatalogModelType, $entity);
        else
            $editForm = $this->createForm(new AutoCatalogType, $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->generateRedirect($type, $parent);
        }

        return [
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'type' => $type,
            'parent' => $parent,
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
        $entity = $em->getRepository('StoCoreBundle:AutoCatalog')->findOneById($id);

        if (!$entity) {
            throw $this->createNotFoundException($this->get('translator')->trans('dict.errors.unable_2_find'));
        }

        $em->remove($entity);
        $em->flush();

        return $this->generateRedirect($type, $parent);
    }

    private function generateRedirect($type, $parent)
    {
        if ($type=="model")
            return $this->redirect($this->generateUrl('admin_autocatalog_show_model', ['id'=>$parent]));
        elseif ($type=="body")
            return $this->redirect($this->generateUrl('admin_autocatalog_show_body', ['id'=>$parent]));
        elseif ($type=="item")
            return $this->redirect($this->generateUrl('admin_autocatalog_show', ['id'=>$parent]));
        else
            return $this->redirect($this->generateUrl('admin_autocatalog'));
    }
}
