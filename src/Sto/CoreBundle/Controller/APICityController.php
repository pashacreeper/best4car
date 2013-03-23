<?php

namespace Sto\CoreBundle\Controller;

use Symfony\Component\Serializer\Serializer,
    Symfony\Component\HttpFoundation\Response,
    Symfony\Component\HttpKernel\Exception\NotFoundHttpException,
    Symfony\Component\HttpKernel\Exception\HttpException,
    Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\FOSRestController,
    FOS\RestBundle\View\View,
    FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sto\CoreBundle\Entity\DictionaryCity as City;

/**
 * City controller.
 */
class APICityController extends FOSRestController
{
    /**
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Create City",
     *  statusCodes={
     *         200="Returned when successful"
     *         }
     * )
     * @Rest\View
     * @Route("/api/city/", name="api_city_add")
     * @Method({"POST"})
     */
    public function addAction()
    {
        $serializer = $this->container->get('serializer');
        // hardcoded "Coming Soon"
        return new Response($serializer->serialize(array("message" => "Permission denied", "type" => "error", "code" => 403), 'json'), 403);
    }

    /**
     *
     * @ApiDoc(
     *  description="Get City By Id",
     *  statusCodes={
     *         200="Returned when successful",
     *         404="Returned when the City is not found"}
     * )
     *
     * @param  integer $id
     * @return City
     *
     * @Rest\View
     * @Route("/api/city/{id}", name="api_city_get", requirements={"id" = "\d+"} )
     * @Method({"GET"})
     */
    public function getAction($id)
    {
        $serializer = $this->container->get('serializer');
        $data = $this->getDoctrine()->getManager()->getRepository('StoCoreBundle:DictionaryCity')->find($id);

        if ($data === NULL)
            return new Response($serializer->serialize(array("message" => "Not found City", "type" => "error", "code" => 404, ), 'json'), 404);
        else
            return new Response($serializer->serialize($data, 'json'));
    }

    /**
     *
     * @ApiDoc(
     *  description="Get All Countries",
     *  statusCodes={
     *         200="Returned when successful"
     *         }
     * )
     *
     * @Rest\View
     * @Route("/api/city/all", name="api_city_all")
     * @Method({"GET"})
     */
    public function allAction()
    {
        $serializer = $this->container->get('serializer');

        $em = $this->getDoctrine()->getManager();
        $data = $em->getRepository('StoCoreBundle:DictionaryCity')->findAll();

        return new Response($serializer->serialize($data, 'json'));

    }

    /**
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Update City",
     *  statusCodes={
     *         200="Returned when successful"
     *         }
     * )
     * @Rest\View
     * @Route("/api/city/", name="api_city_update")
     * @Method({"PUT"})
     */
    public function updateAction()
    {
        $serializer = $this->container->get('serializer');
        // hardcoded "Coming Soon"
        return new Response($serializer->serialize(array("message" => "Permission denied", "type" => "error", "code" => 403), 'json'), 403);
    }

   /**
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Delete City",
     *  statusCodes={
     *         200="Returned when successful"
     *         }
     * )
     *
     * @param integer $id
     *
     * @Rest\View
     * @Route("/api/city/", name="api_city_delete", requirements={"id" = "\d+"} )
     *
     * @Method({"DELETE"})
     */
    public function deleteAction()
    {
        $serializer = $this->container->get('serializer');
        // hardcoded "Coming Soon"
        return new Response($serializer->serialize(array("message" => "Permission denied", "type" => "error", "code" => 403), 'json'), 403);
    }
}
