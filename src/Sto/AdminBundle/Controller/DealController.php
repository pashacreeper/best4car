<?php

namespace Sto\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request,
    Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sto\CoreBundle\Entity\Deal,
    Sto\AdminBundle\Form\DealType;

/**
 * Deal controller.
 *
 * @Route("/deal")
 */
class DealController extends Controller
{
    /**
     * Lists all Deal entities.
     *
     * @Route("/", name="deals")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $deals = $em->getRepository('StoCoreBundle:Deal')->findAll();

        return [
            'deals' => $deals,
        ];
    }

    /**
     * Displays a form to create a new Deal entity.
     *
     * @Route("/new", name="deal_new")
     * @Template()
     */
    public function newAction()
    {
        $form = $this->createForm(new DealType, new Deal);

        return [
            'form' => $form->createView()
        ];
    }

    /**
     * Creates a new Deal entity.
     *
     * @Route("/create", name="deal_create")
     * @Method("POST")
     * @Template("StoCoreBundle:Deal:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $deal = new Deal;
        $form = $this->createForm(new DealType, $deal);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($deal);
            $em->flush();

            return $this->redirect($this->generateUrl('deals'));
        }

        return [
            'deal' => $deal,
            'form' => $form->createView(),
        ];
    }

    /**
     * Displays a form to edit an existing Deal entity.
     *
     * @Route("/{id}/edit", name="deal_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $deal = $em->getRepository('StoCoreBundle:Deal')->findOneById($id);

        if (!$deal) {
            throw $this->createNotFoundException('Unable to find Deal entity.');
        }

        $editForm = $this->createForm(new DealType, $deal);

        return [
            'deal'      => $deal,
            'edit_form' => $editForm->createView(),
        ];
    }

    /**
     * Edits an existing Deal entity.
     *
     * @Route("/{id}/update", name="deal_update")
     * @Method("POST")
     * @Template("StoCoreBundle:Deal:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $deal = $em->getRepository('StoCoreBundle:Deal')->findOneById($id);

        if (!$deal) {
            throw $this->createNotFoundException('Unable to find Deal entity.');
        }

        $editForm = $this->createForm(new DealType, $deal);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($deal);
            $em->flush();

            return $this->redirect($this->generateUrl('deals'));
        }

        return array(
            'deal'      => $deal,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Deletes a Deal entity.
     *
     * @Route("/{id}/delete", name="deal_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('StoCoreBundle:Deal')->findOneById($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Deal entity.');
        }

        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('deals'));
    }
}
