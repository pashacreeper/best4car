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
            return new Response(404, 'Not found feddback id');
        }

        $em = $this->getDoctrine()->getManager();
        $feedback = $em->getRepository('StoCoreBundle:Feedback')->findOneById($feedback_id);
        if (!$feedback) {
            return new Response(404, 'Not found feedback');
        }

        $feedback->addPlus();
        $em->persist($feedback);
        $em->flush();

        return new Response($serializer->serialize(['pluses'=>$feedback->getPluses(), 'id'=>$feedback_id], 'json'));
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

        $feedback->addMinus();
        $em->persist($feedback);
        $em->flush();

        return new Response($serializer->serialize(['minuses'=>$feedback->getMinuses(), 'id'=>$feedback_id], 'json'));
    }
}
