<?php

namespace Sto\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller,
    Symfony\Component\Serializer\Serializer,
    Symfony\Component\HttpFoundation\Response,
    Symfony\Component\HttpKernel\Exception\NotFoundHttpException,
    Symfony\Component\HttpKernel\Exception\HttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\FOSRestController,
    FOS\RestBundle\View\View,
    FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sto\CoreBundle\Entity\Dictionary\Country;

/**
 * Country controller.
 *
 * @Route("/api/country")
 */
class APICountryController extends APIBaseController
{

    /**
     * @ApiDoc(
     *      description="Получить страну по Id",
     *      statusCodes={
     *         200="Returned when successful",
     *         404="Returned when the Country is not found"
     *      }
     * )
     * @Rest\View
     * @Route("/{id}", name="api_country_get", requirements={"id" = "\d+"} )
     * @Method({"GET"})
     */

    public function getAction($id)
    {
        return parent::getAction($id);
    }

    /**
     * @ApiDoc(
     *  description="Получить все страны",
     *  statusCodes={
     *         200="Returned when successful"
     *         }
     * )
     * @Rest\View
     * @Route("/all", name="api_country_all")
     * @Method({"GET"})
     */
    public function allAction()
    {
        $serializer = $this->container->get('jms_serializer');

        $em = $this->getDoctrine()->getManager();
        $data = $em->getRepository('StoCoreBundle:Dictionary\Country')
            ->createQueryBuilder('dictionary')
            ->where('dictionary.parent is null')
            ->getQuery()
            ->getArrayResult()
        ;

        return new Response($serializer->serialize($data, 'json'));
    }

}
