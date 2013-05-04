<?php

namespace Sto\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request,
    Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sto\CoreBundle\Entity\Feedback,
    Sto\CoreBundle\Entity\FeedbackAnswer,
    Sto\CoreBundle\Entity\FeedbackCompany,
    Sto\CoreBundle\Entity\FeedbackDeal,
    Sto\AdminBundle\Form\FeedbackType,
    Sto\AdminBundle\Form\FeedbackCompanyType,
    Sto\AdminBundle\Form\FeedbackDealType,
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
        $filter_company = $session->get('filter_company');

        $query = $em->getRepository('StoCoreBundle:Feedback')->createQueryBuilder('f');
        if (isset($filter_published) && $filter_published != -1) {
            $flag = ($filter_published == 2 ) ? 0 : 1;
            $query->where('f.published = :flag')
                ->setParameter('flag', $flag);

        }

        if (isset($filter_company) && $filter_company!=-1) {
            $query->andWhere('f.companyId = :company')
                ->setParameter('company', $filter_company   );
        }

        if ($filter_answer == 1) {
            $query->leftJoin('f.feedbackAnswer', 'fa')
                ->andWhere('fa.feedbackId is not null');
        } elseif ($filter_answer == 2) {
            $query->leftJoin('f.feedbackAnswer', 'fa')
                ->andWhere('fa.feedbackId is null');
        }

        $query->orderBy('f.id', 'DESC')
            ->getQuery();

        $def_limit = $this->container->getParameter('pagination_default_value');

        $pagination = $this->get('knp_paginator')->paginate(
            $query,
            $this->get('request')->query->get('page', 1),
            $this->get('request')->query->get('numItemsPerPage', $def_limit)
        );

        $parents = $em->getRepository('StoCoreBundle:Company')
            ->createQueryBuilder('entity')
            ->orderBy('entity.name')
            ->getQuery()
            ->getResult();

        return array(
            'entities' => $pagination,
            'parents' => $parents,
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
            'entity' => $entity,
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
        if ($this->get('security.context')->isGranted('ROLE_MANAGER')) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('StoCoreBundle:Feedback')->find($feedback_id);

            if ($entity instanceof FeedbackCompany) {
                $company = $entity->getCompany();
                if (!in_array($this->getUser(), $company->getArrayManagers()) ) {
                    $this->get('session')->getFlashBag()->add('notice', 'You are not a manager of this company!');

                    return $this->redirect($this->generateUrl('feedbacks_show', array('id' => $entity->getId())));
                }
            } elseif ($entity instanceof FeedbackDeal) {
                $deal = $entity->getDeal();
                $company = $deal->getCompany();
                if (!in_array($this->getUser(), $company->getArrayManagers()) ) {
                    $this->get('session')->getFlashBag()->add('notice', 'You are not a manager of this company!');

                    return $this->redirect($this->generateUrl('feedbacks_show', array('id' => $entity->getId())));
                }
            }

            if ($id!=0) {
                $entity_answer = $em->getRepository('StoCoreBundle:FeedbackAnswer')->find($id);
            }

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
                $entity_answer->setOwner($this->getUser());
                $entity_answer->setDate(new \DateTime("now"));
                $entity_answer->setFeedback($entity);
                $em2->persist($entity_answer);
                $em2->flush();
                $entity->setFeedbackAnswer($entity_answer);
                $em->persist($entity);
                $em->flush();

                $this->get('session')->getFlashBag()->add('saved_notice', 'Answer was saved!');

                return $this->redirect($this->generateUrl('feedbacks_show', array('id' => $entity->getId())));
            }

            return array(
                'entity'      => $entity,
                'form' => $answer_form->createView(),
            );
        }
    }

    /**
     * Displays a form to create a new Feedback entity.
     *
     * @Route("/new/{feedback}", name="feedbacks_new")
     * @Template()
     */
    public function newAction($feedback)
    {
        if ($feedback=='company') {
            $form   = $this->createForm(new FeedbackCompanyType, new FeedbackCompany);
        } elseif ($feedback=='deal') {
            $form   = $this->createForm(new FeedbackDealType, new FeedbackDeal);
        }

        return array(
            'form'   => $form->createView(),
            'feedback_type' => $feedback,
        );
    }

    /**
     * Creates a new Feedback entity.
     *
     * @Route("/create/{feedback}", name="feedbacks_create")
     * @Method("POST")
     * @Template("StoAdminBundle:Feedback:new.html.twig")
     */
    public function createAction(Request $request, $feedback)
    {
        if ($feedback=='company') {
            $entity  = new FeedbackCompany();
            $form = $this->createForm(new FeedbackCompanyType(), $entity);
        } elseif ($feedback=='deal') {
            $entity  = new FeedbackDeal();
            $form = $this->createForm(new FeedbackDealType(), $entity);
        }
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->setUser($this->getUser());
            $entity->setPluses(0);
            $entity->setMinuses(0);
            $entity->setIp($request->getClientIp());
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('feedbacks_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'feedback_type' => $feedback,
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

        $editForm = $this->createForm(new FeedbackType, $entity);
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
            $this->get('session')->setFlash('notice', 'Feedback was saved!');

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
     * @Method("GET")
     */
    public function deleteAction(Request $request, $id)
    {

        $em = $this->getDoctrine()->getManager();
        $feedback = $em->getRepository('StoCoreBundle:Feedback')->findOneById($id);

        if (!$feedback) {
            throw $this->createNotFoundException('Unable to find Feedback entity.');
        }

        $em->remove($feedback);
        $em->flush();

        $this->get('session')->setFlash('notice', 'Feedback was removed!');

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
        $filter_company = $request->request->get('filter_company');

        $session = $this->get('session');

        $session->set('filter_published', $filter_published);
        $session->set('filter_answer', $filter_answer);
        $session->set('filter_company', $filter_company);

        return $this->redirect($this->generateUrl('feedbacks'));
    }
}
