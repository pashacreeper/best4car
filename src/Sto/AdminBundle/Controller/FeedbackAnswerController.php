<?php

namespace Sto\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request,
    Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sto\CoreBundle\Entity\FeedbackAnswer,
    Sto\AdminBundle\Form\FeedbackAnswerType;

/**
 * FeedbackAnswer controller.
 *
 * @Route("/feedback/answer")
 */
class FeedbackAnswerController extends Controller
{
    /**
     * Lists all FeedbackAnswer entities.
     *
     * @Route("/", name="feedbackanswer")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('StoCoreBundle:FeedbackAnswer')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a FeedbackAnswer entity.
     *
     * @Route("/{id}/show", name="feedbackanswer_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('StoCoreBundle:FeedbackAnswer')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FeedbackAnswer entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new FeedbackAnswer entity.
     *
     * @Route("/new", name="feedbackanswer_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new FeedbackAnswer();
        $form   = $this->createForm(new FeedbackAnswerType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new FeedbackAnswer entity.
     *
     * @Route("/create", name="feedbackanswer_create")
     * @Method("POST")
     * @Template("StoCoreBundle:FeedbackAnswer:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new FeedbackAnswer();
        $form = $this->createForm(new FeedbackAnswerType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('feedbackanswer_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing FeedbackAnswer entity.
     *
     * @Route("/{id}/edit", name="feedbackanswer_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('StoCoreBundle:FeedbackAnswer')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FeedbackAnswer entity.');
        }

        $editForm = $this->createForm(new FeedbackAnswerType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing FeedbackAnswer entity.
     *
     * @Route("/{id}/update", name="feedbackanswer_update")
     * @Method("POST")
     * @Template("StoCoreBundle:FeedbackAnswer:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('StoCoreBundle:FeedbackAnswer')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FeedbackAnswer entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new FeedbackAnswerType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('feedbackanswer_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a FeedbackAnswer entity.
     *
     * @Route("/{id}/delete", name="feedbackanswer_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('StoCoreBundle:FeedbackAnswer')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find FeedbackAnswer entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('feedbackanswer'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
