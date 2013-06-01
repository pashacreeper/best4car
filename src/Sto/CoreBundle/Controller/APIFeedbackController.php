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
            return new Response(404, 'Not found feedback id');
        }

        $em = $this->getDoctrine()->getManager();

        $feedback = $em->getRepository('StoCoreBundle:Feedback')->findOneById($feedback_id);
        if (!$feedback) {
            return new Response(404, 'Not found feedback');
        }

        $user = $this->getUser();
        $evaluation = $em->getRepository('StoCoreBundle:FeedbackEvaluation')->findOneBy(['feedback'=>$feedback_id, 'user'=>$user->getId()]);

        if (count($evaluation)==0) {
            $evaluation = new FeedbackEvaluation($user, $feedback, true);
            $feedback->addPlus();
            $em->persist($evaluation);
            $em->flush();
        } else
            if (!$evaluation->getReview()) {
                // если есть дизлайк - меняем знак на противоположный и корректируем +/-
                $evaluation->setReview(true);
                $feedback->subMinus();
                $em->persist($evaluation);
                $em->flush();
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
            return new Response(404, 'Not found feddback id');
        }

        $em = $this->getDoctrine()->getManager();
        $feedback = $em->getRepository('StoCoreBundle:Feedback')->findOneById($feedback_id);
        if (!$feedback) {
            return new Response(404, 'Not found feedback');
        }

        $user = $this->getUser();
        $evaluation = $em->getRepository('StoCoreBundle:FeedbackEvaluation')->findOneBy(['feedback'=>$feedback_id, 'user'=>$user->getId()]);

        if (count($evaluation)==0) {
            $evaluation = new FeedbackEvaluation($user, $feedback, false);
            $feedback->addMinus();
            $em->persist($evaluation);
            $em->flush();
        } else
            if ($evaluation->getReview()) {
                // если есть лайк - меняем знак на противоположный и корректируем +/-
                $evaluation->setReview(false);
                $feedback->subPlus();
                $em->persist($evaluation);
                $em->flush();
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
}
