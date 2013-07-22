<?php

namespace Sto\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use JMS\SecurityExtraBundle\Annotation\Secure;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sto\CoreBundle\Entity\Feedback;
use Sto\CoreBundle\Entity\FeedbackAnswer;
use Sto\UserBundle\Entity\User;
use Sto\CoreBundle\Entity\FeedbackEvaluation;

/**
 * APi Feedback controller.
 *
 * @Route("/api/feedback")
 */
class APIFeedbackController extends FOSRestController
{
    /**
     * @ApiDoc(
     * description="Добавить лайк к отзыву",
     *     statusCodes={
     *         200="Returned when successful",
     *     }
     * )
     *
     * @Rest\View
     * @Route("/add-like", name="api_add_like", options={"expose"=true})
     * @Secure(roles="IS_AUTHENTICATED_FULLY")
     */
    public function addLike(Request $request)
    {
        $serializer = $this->container->get('jms_serializer');

        if ($request->get('feedback_id')) {
            $feedback_id = $request->get('feedback_id');
        } else {
            return new Response('Not found feedback id', 404);
        }

        $em = $this->getDoctrine()->getManager();

        $feedback = $em->getRepository('StoCoreBundle:Feedback')->findOneById($feedback_id);
        if (!$feedback) {
            return new Response('Not found feedback', 404);
        }

        $user = $this->getUser();
        $evaluation = $em->getRepository('StoCoreBundle:FeedbackEvaluation')->findOneBy(['feedback'=>$feedback_id, 'user'=>$user->getId()]);

        if (count($evaluation) == 0) {
            $evaluation = new FeedbackEvaluation(true, $user, $feedback);
            $feedback->addPlus();
            $em->persist($evaluation);
            $em->flush();
        } else {
            if (!$evaluation->getReview()) {
                // если есть дизлайк - меняем знак на противоположный и корректируем +/-
                $evaluation->setReview(true);
                $feedback->subMinus();
                $em->persist($evaluation);
                $em->flush();
            }
        }

        return new Response($serializer->serialize(['pluses'=>$feedback->getPluses(), 'minuses'=>$feedback->getMinuses(), 'id'=>$feedback_id, 'user' => $user->getId(), 'val'=>$evaluation->getReview(), 'msg' => '2'], 'json'));
    }

    /**
     * @ApiDoc(
     * description="Добавить дизлайк к отзыву",
     *     statusCodes={
     *         200="Returned when successful",
     *     }
     * )
     *
     * @Rest\View
     * @Route("/add-dislike", name="api_add_dislike", options={"expose"=true})
     * @Secure(roles="IS_AUTHENTICATED_FULLY")
     */
    public function addDislike(Request $request)
    {
        $serializer = $this->container->get('jms_serializer');

        if ($request->get('feedback_id')) {
            $feedback_id = $request->get('feedback_id');
        } else {
            return new Response('Not found feddback id', 404);
        }

        $em = $this->getDoctrine()->getManager();
        $feedback = $em->getRepository('StoCoreBundle:Feedback')->findOneById($feedback_id);
        if (!$feedback) {
            return new Response('Not found feedback', 404);
        }

        $user = $this->getUser();
        $evaluation = $em->getRepository('StoCoreBundle:FeedbackEvaluation')->findOneBy(['feedback'=>$feedback_id, 'user'=>$user->getId()]);

        if (count($evaluation)==0) {
            $evaluation = new FeedbackEvaluation(false, $user, $feedback);
            $feedback->addMinus();
            $em->persist($evaluation);
            $em->flush();
        } else {
            if ($evaluation->getReview()) {
                // если есть лайк - меняем знак на противоположный и корректируем +/-
                $evaluation->setReview(false);
                $feedback->subPlus();
                $em->persist($evaluation);
                $em->flush();
            }
        }

        return new Response($serializer->serialize(['pluses'=>$feedback->getPluses(), 'minuses'=>$feedback->getMinuses(), 'id'=>$feedback_id, 'val'=>$evaluation->getReview()], 'json'));
    }

    /**
     * @ApiDoc(
     *  description="Добавить ответ на отзыв",
     *  statusCodes={
     *         200="Returned when successful",
     *         404="Returned when the Feedback is not found"}
     * )
     *
     * @return Feedback
     *
     * @Rest\View
     * @Route("/add-answer", name="api_add_answer", options={"expose"=true} )
     */
    public function addAnswerAction(Request $request)
    {
        $serializer = $this->container->get('jms_serializer');

        $feedback_id = $request->get('feedback_id');
        $answer = $request->get('answer');

        $em = $this->getDoctrine()->getManager();
        $feedback = $em->getRepository('StoCoreBundle:Feedback')->findOneById($feedback_id);
        if (!$feedback)
            return new Response($serializer->serialize(["message" => "Not found Feedback ".$feedback_id, "type" => "error", "code" => 404], 'json'));
        $oAnswer = new FeedbackAnswer();
        $oAnswer->setAnswer($answer);
        $oAnswer->setOwner($this->getUser());
        $oAnswer->setFeedback($feedback);
        $oAnswer->setDate(new \DateTime('now'));
        $em->persist($oAnswer);
        $em->flush();

        $data = $this->render('StoContentBundle:Company:feedback_answer.html.twig', ['feedback'=>$feedback]);

        return new Response($serializer->serialize($data, 'json'));
    }

    /**
     * @ApiDoc(
     *  description="Добавить ответ на отзыв",
     *  statusCodes={
     *         200="Returned when successful",
     *         404="Returned when the Feedback is not found"}
     * )
     *
     * @return Feedback
     *
     * @Rest\View
     * @Route("/edit-answer", name="api_edit_answer", options={"expose"=true} )
     */
    public function editAnswerAction(Request $request)
    {
        $serializer = $this->container->get('jms_serializer');
        $feedback_id = $request->get('feedback_id');
        $answer = $request->get('answer');
        $answer_id = $request->get('answer_id');

        $em = $this->getDoctrine()->getManager();
        $oAnswer = $em->getRepository('StoCoreBundle:FeedbackAnswer')->findOneById($answer_id);
        if (!$oAnswer) {
            return new Response($serializer->serialize(["message" => "Not found FeedbackAnswer ".$answer_id, "type" => "error", "code" => 404], 'json'));
        }

        $oAnswer->setAnswer($answer);
        $em->persist($oAnswer);
        $em->flush();

        return new Response($serializer->serialize(["message" => "Done", "type" => "successful", "code" => 200], 'json'));
    }

    /**
     * @ApiDoc(
     * description="ответ на сортировку или фильтр",
     *     statusCodes={
     *         200="Returned when successful",
     *     }
     * )
     * @Rest\View
     * @Route("/sort-filter", name="api_sort_filter", options={"expose"=true})
     */
    public function sort_filter(Request $request)
    {
        $serializer = $this->container->get('jms_serializer');

        if (! $request->get('sort-tab')) {
            return new Response('Not found sort tabs', 404);
        }
        if (! $request->get('sort-tab')) {
            return new Response('Not found filter tabs', 404);
        }
        if (! $request->get('entity-id')) {
            return new Response('Not found entity (company or deal) id',404);
        }
        if (! $request->get('entity-type')) {
            return new Response('Not found entity type (company or deal)',404);
        }

        $sortTab = $request->get('sort-tab');
        $filterTab = $request->get('filter-tab');
        $entityId = $request->get('entity-id');
        $entityType = $request->get('entity-type');

        $em = $this->getDoctrine()->getManager();
        if ($entityType == 'company') {
            $qb = $em->getRepository('StoCoreBundle:FeedbackCompany')
                ->createQueryBuilder('fc')
                ->where('fc.companyId = :company')
                ->setParameter('company', $entityId)
            ;
        } elseif ($entityType == 'deal') {
            $qb = $em->getRepository('StoCoreBundle:FeedbackDeal')
                ->createQueryBuilder('fc')
                ->where('fc.dealId = :deal')
                ->setParameter('deal', $entityId)
            ;
        } else {
            return new Response('Entity type is not valid (company or deal)',404);
        }

        switch ($filterTab) {
            case("positive"):
                $qb->andWhere('fc.pluses > fc.minuses');
                break;
            case("negative"):
                $qb->andWhere('fc.pluses < fc.minuses');
                break;
            case("useful"):
                $qb->andWhere('fc.pluses > fc.minuses');
                break;
        }

        if ($sortTab == "sort-rating") {
            $qb->orderBy("fc.feedbackRating","DESC");
        } else {
            $qb->orderBy("fc.date","DESC");
        }
        $query = $qb->getQuery();

        $feedbacks = $this->get('knp_paginator')->paginate(
            $query,
            $this->get('request')->query->get('page',1),
            10
        );

        if ($entityType=='company') {
            if ($this->getUser()) {
                $manager = $em->getRepository('Sto\CoreBundle\Entity\CompanyManager')
                    ->createQueryBuilder('m')
                    ->select('m')
                    ->join('m.company', 'company')
                    ->where('m.id = :user_id AND company.id = :company')
                    ->setParameter('user_id', $this->getUser()->getId())
                    ->setParameter('company', $entityId)
                    ->getQuery()
                    ->getResult()
                ;
            }
        }

        $isManager = (isset($manager) && count($manager) > 0) ? true : false;

        $date = new \DateTime();
        $date->modify('-15 hours');

        if ($entityType=='company') {
            $content = $this->renderView(
                'StoContentBundle:Company:feedbacks.html.twig',
                [
                    'feedbacks' => $feedbacks,
                    'companyId' => $entityId,
                    'isManager' => $isManager,
                    'date' => $date
                ]
            );
        } else {
            $content = $this->renderView(
                'StoContentBundle:Deal:feedbacks.html.twig',
                [
                    'feedbacks' => $feedbacks,
                    'dealId' => $entityId,
                    'isManager' => $isManager,
                    'date' => $date
                ]
            );
        }

        return new Response($content, 200);
    }

    /**
     * @ApiDoc(
     *     description="Жалоба",
     *     statusCodes={
     *         200="Returned when successful",
     *     }
     * )
     * @Rest\View
     * @Route("/add-complain", name="api_feedback_add_complain", options={"expose"=true})
     * @Secure(roles="IS_AUTHENTICATED_FULLY")
     */
    public function addComplain(Request $request)
    {
        $serializer = $this->container->get('jms_serializer');

        if (!$request->get('data')) {

            if (!$request->get('feedback_id'))
                return new Response(404, 'Not found parameter feddback id');

            $feedback_id = $request->get('feedback_id');

            $em = $this->getDoctrine()->getManager();
            $feedback = $em->getRepository('StoCoreBundle:Feedback')->findOneById($feedback_id);
            if (!$feedback) {
                return new Response(404, 'Not Found Feedback');
            }

            $feedback->setComplain(true);
            $em->persist($feedback);
            $em->flush();

            return new Response($serializer->serialize(['id'=>$feedback_id, 'complain' => $feedback->isComplain()], 'json'));
        } else {
            if (!$request->get('answer_id'))
                return new Response(404, 'Not found parameter answer_id');

            $answer_id = $request->get('answer_id');

            $em = $this->getDoctrine()->getManager();
            $answer = $em->getRepository('StoCoreBundle:FeedbackAnswer')->findOneById($answer_id);
            if (!$answer) {
                return new Response(404, 'Not Found answer');
            }
            $feedback_id = $answer->getFeedback()->getId();
            $answer->setComplain(true);
            $em->persist($answer);
            $em->flush();

            return new Response($serializer->serialize(['answer_id'=>$answer->GetId(),'id'=>$feedback_id, 'complain' => $answer->isComplain()], 'json'));

        }
    }

    /**
     * @ApiDoc(
     * description="Устанавливает параметр",
     *     statusCodes={
     *         200="Returned when successful",
     *     }
     * )
     *
     * @Rest\View
     * @Route("/set-parameter", name="api_feedback_set_parameter", options={"expose"=true})
     * @Secure(roles="ROLE_MODERATOR")
     */
    public function setParameter(Request $request)
    {
        $serializer = $this->container->get('jms_serializer');
        if (!$request->get('type') )
           return new Response(404, 'Not found parameter type');

       if ($request->get('type') == 'feedback-id') {

        $feedback_id = $request->get('id');

        $em = $this->getDoctrine()->getManager();
        $feedback = $em->getRepository('StoCoreBundle:Feedback')->findOneById($feedback_id);
        if (!$feedback) {
            return new Response(404, 'Not Found Feedback');
        }

        if (!$request->get('field') || !$request->get('value')) {
            return new Response(404, 'Not Found Parameter');
        }
        if ($request->get('field') == 'Hidden') {
            $value = (($feedback->isHidden()) xor true);

        } else {
            $value = $request->get('value');
        }

        $method = 'set'.$request->get('field');
        $feedback->{$method}($value);
        $em->persist($feedback);
        $em->flush();

        return new Response($serializer->serialize(['id'=>$feedback_id, 'field' => $request->get('field'), 'value' => $request->get('value')], 'json'));
    } else {
        $answer_id = $request->get('id');

        $em = $this->getDoctrine()->getManager();
        $answer = $em->getRepository('StoCoreBundle:FeedbackAnswer')->findOneById($answer_id);
        if (!$answer) {
            return new Response(404, 'Not Found answer');
        }

        if (!$request->get('field') || !$request->get('value')) {
            return new Response(404, 'Not Found Parameter');
        }
        if ($request->get('field') == 'Hidden') {
            $value = (($answer->isHidden()) xor true);

        } else {
            $value = $request->get('value');
        }
        $method = 'set'.$request->get('field');
        $answer->{$method}($value);
        $em->persist($answer);
        $em->flush();

        return new Response($serializer->serialize(['id'=>$answer_id, 'field' => $request->get('field'), 'value' => $request->get('value')], 'json'));
    }
}

    /**
     * @ApiDoc(
     * description="Удаление отзыва",
     *     statusCodes={
     *         200="Returned when successful",
     *     }
     * )
     *
     * @Rest\View
     * @Route("/delete-feedback", name="api_feedback_delete", options={"expose"=true})
     * @Secure(roles="ROLE_MODERATOR")
     */
    public function deleteFeedback(Request $request)
    {
        $serializer = $this->container->get('jms_serializer');
        if (!$request->get('type') )
            return new Response(404, 'Not found parameter type');

        if ($request->get('type') == 'feedback-id') {
            $feedback_id = $request->get('id');

            $em = $this->getDoctrine()->getManager();
            $feedback = $em->getRepository('StoCoreBundle:Feedback')->findOneById($feedback_id);
            if (!$feedback) {
                return new Response(404, 'Not Found Feedback');
            }

            $em->remove($feedback);
            $em->flush();

            return new Response($serializer->serialize(['id'=>$feedback_id], 'json'));
        } else {
            $answer_id = $request->get('id');

            $em = $this->getDoctrine()->getManager();
            $answer = $em->getRepository('StoCoreBundle:FeedbackAnswer')->findOneById($answer_id);
            if (!$answer) {
                return new Response(404, 'Not Found answer');
            }
            $id = $answer->getFeedback()->getId();
            $em->remove($answer);
            $em->flush();

            return new Response($serializer->serialize(['id'=>$id], 'json'));
        }
    }
    /**
     * @ApiDoc(
     * description="Проверяет состояние отзыва (скрыт/жалоба)",
     *     statusCodes={
     *         200="Returned when successful",
     *     }
     * )
     *
     * @Rest\View
     * @Route("/state-feedback", name="api_feedback_state", options={"expose"=true})
     */
    public function stateFeedback(Request $request)
    {
        $serializer = $this->container->get('jms_serializer');
        if (!$request->get('type') )
            return new Response(404, 'Not found parameter type');
        if ($request->get('type') == 'feedback-id') {

            $feedback_id = $request->get('id');

            $em = $this->getDoctrine()->getManager();
            $feedback = $em->getRepository('StoCoreBundle:Feedback')->findOneById($feedback_id);
            if (!$feedback) {
                return new Response(404, 'Not Found Feedback');
            }
        } else {
            $answer_id = $request->get('id');

            $em = $this->getDoctrine()->getManager();
            $feedback = $em->getRepository('StoCoreBundle:FeedbackAnswer')->findOneById($answer_id);
            if (!$feedback) {
                return new Response(404, 'Not Found FeedbackAnswer');
            }
        }

        return new Response($serializer->serialize(['hidden'=>$feedback->isHidden(),'complain'=>$feedback->isComplain()], 'json'));
    }
}
