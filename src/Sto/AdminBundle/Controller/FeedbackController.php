<?php

namespace Sto\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request,
    Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sto\CoreBundle\Entity\Feedback,
    Sto\CoreBundle\Entity\FeedbackAnswer,
    Sto\AdminBundle\Form\FeedbackType,
    Sto\AdminBundle\Form\FeedbackAnswerType;

/**
 * Feedback controller.
 *
 * @Route("/feedbacks")
 */
class FeedbackController extends Controller
{
    /**
     * Lists all Feedback entities.
     *
     * @Route("/", name="feedbacks")
     * @Template("StoAdminBundle:Feedback:index.html.twig")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $session = $this->get('session');
        $filter_published = $session->get('filter_published');
        $filter_answer = $session->get('filter_answer');
        if (isset($filter_published) && $filter_published>-1) {
            $flag = ($filter_published == 2 ) ? 0 : 1;
            $entities = $em->getRepository('StoCoreBundle:Feedback')
                ->findByIsPublished($flag);
            if (isset($filter_answer) && $filter_answer>-1) {
                foreach ($entities as $key => $value) {
                    if ($filter_answer == 1 && !$value->getFeedbackAnswer())
                        unset($entities[$key]);
                    elseif ($filter_answer == 2 && $value->getFeedbackAnswer())
                        unset($entities[$key]);
                }
            }
        } else {
            $entities = $em->getRepository('StoCoreBundle:Feedback')->findAll();
            if (isset($filter_answer) && $filter_answer>-1) {
                foreach ($entities as $key => $value) {
                    if ($filter_answer == 1 && !$value->getFeedbackAnswer())
                        unset($entities[$key]);
                    elseif ($filter_answer == 2 && $value->getFeedbackAnswer())
                        unset($entities[$key]);
                }
            }
        }

        $def_limit = $this->container->getParameter('pagination_default_value');

        $pagination = $this->get('knp_paginator')->paginate(
            $entities,
            $this->get('request')->query->get('page', 1),
            $this->get('request')->query->get('numItemsPerPage', $def_limit)
        );

        return array(
            'entities' => $pagination,
        );
    }

    /**
     * Finds and displays a Feedback entity.
     *
     * @Route("/{id}/show", name="feedbacks_show")
     * @Template()
     */
    public function showAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('StoCoreBundle:Feedback')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Feedback entity.');
        }

        if (!$entity->getFeedbackAnswer()) {
            $entity_answer = new FeedbackAnswer();
            $answer_form = $this->createForm(new FeedbackAnswerType, $entity_answer);
        } else {
            $entity_answer = $entity->getFeedbackAnswer();
            $answer_form = $this->createForm(new FeedbackAnswerType, $entity_answer);
        }

        return array(
            'entity'      => $entity,
            'form' => $answer_form->createView(),
        );
    }

    /**
     * Finds and displays a Feedback entity.
     *
     * @Route("/{feedback_id}/{id}/save", name="save_answer", defaults={"id" = 0})
     * @Template()
     */
    public function saveAnswerAction(Request $request, $feedback_id, $id=0)
    {
        if ($id!=0) {
            $em = $this->getDoctrine()->getManager();
            $entity_answer = $em->getRepository('StoCoreBundle:FeedbackAnswer')->find($id);
        }

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('StoCoreBundle:Feedback')->find($feedback_id);

        if (!$entity->getFeedbackAnswer()) {
            $entity_answer = new FeedbackAnswer();
            $answer_form = $this->createForm(new FeedbackAnswerType, $entity_answer);
        } else {
            $entity_answer = $entity->getFeedbackAnswer();
            $answer_form = $this->createForm(new FeedbackAnswerType, $entity_answer);
        }

        $answer_form->bind($request);
        if ($answer_form->isValid()) {

            $em2 = $this->getDoctrine()->getManager();
            $entity_answer->setManager($this->getUser());
            $entity_answer->setDate(new \DateTime("now"));
            $entity_answer->setFeedback($entity);
            $em2->persist($entity_answer);
            $em2->flush();
            $entity->setFeedbackAnswer($entity_answer);
            $em->persist($entity);
            $em->flush();

            $this->get('session')->setFlash('notice', 'Your answer were saved!');

            return $this->redirect($this->generateUrl('feedbacks_show', array('id' => $entity->getId())));
        }

        return array(
            'entity'      => $entity,
            'form' => $answer_form->createView(),
        );
    }

    /**
     * Displays a form to create a new Feedback entity.
     *
     * @Route("/new", name="feedbacks_new")
     * @Template()
     */
    public function newAction()
    {
        $form   = $this->createForm(new FeedbackType, new Feedback);

        return array(
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new Feedback entity.
     *
     * @Route("/create", name="feedbacks_create")
     * @Method("POST")
     * @Template("StoAdminBundle:Feedback:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Feedback();
        $form = $this->createForm(new FeedbackType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->setUser($this->getUser());
            $entity->setPluses(0);
            $entity->setMinuses(0);
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('feedbacks_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Feedback entity.
     *
     * @Route("/{id}/edit", name="feedbacks_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('StoCoreBundle:Feedback')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Feedback entity.');
        }

        $editForm = $this->createForm(new FeedbackType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Feedback entity.
     *
     * @Route("/{id}/update", name="feedbacks_update")
     * @Method("POST")
     * @Template("StoAdminBundle:Feedback:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('StoCoreBundle:Feedback')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Feedback entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new FeedbackType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {

            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('feedbacks_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Feedback entity.
     *
     * @Route("/{id}/delete", name="feedbacks_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('StoCoreBundle:Feedback')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Feedback entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('feedbacks'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }

    /**
     * Set Filter for a feedback lists.
     *
     * @Route("/filter", name="feedback_filter")
     * @Method("POST")
     */
    public function filterAction(Request $request)
    {
        $filter_published = $request->request->get('filter_published');
        $filter_answer = $request->request->get('filter_answer');

        $session = $this->get('session');

        $session->set('filter_published', $filter_published);

        $session->set('filter_answer', $filter_answer);
        //$session->set('filter_city_name', $filter_name);
        return $this->redirect($this->generateUrl('feedbacks'));
    }
}
