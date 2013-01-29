<?php
namespace Sto\CoreBundle\Controller;

use Symfony\Component\Serializer\Serializer,
    Symfony\Component\HttpFoundation\Response,
    Symfony\Component\HttpKernel\Exception\NotFoundHttpException,
    Symfony\Component\HttpKernel\Exception\HttpException;

use Doctrine\ORM\Mapping as ORM;

use FOS\RestBundle\Controller\FOSRestController,
    FOS\RestBundle\View\View,
    FOS\RestBundle\Controller\Annotations as Rest;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
// Entity
use Sto\CoreBundle\Entity\Country;



/**
 * Country controller.
 *
 */
class APICountryController extends FOSRestController
{
    /**
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Create Country",
     *  statusCodes={
     *         200="Returned when successful"
     *         }
     * )
     * @Rest\View
     * @Route("/api/country/", name="api_country_add")
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
     *  description="Get Country By Id",
     *  statusCodes={
     *         200="Returned when successful",
     *         404="Returned when the Country is not found"}
     * )
     *
     * @param  integer $id
     * @return Country
     *
     * @Rest\View
     * @Route("/api/country/{id}", name="api_country_get", requirements={"id" = "\d+"} )
     * @Method({"GET"})
     */

    public function getAction($id)
    {

        $serializer = $this->container->get('serializer');
        $data = $this->getDoctrine()->getManager()->getRepository('StoCoreBundle:Country')->find($id);

        if ($data === NULL)
            return new Response($serializer->serialize(array("message" => "Not found Country", "type" => "error", "code" => 404, ), 'json'), 404);
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
     * @Route("/api/country/all", name="api_country_all")
     * @Method({"GET"})
     */
    public function allAction()
    {
        $serializer = $this->container->get('serializer');

        $em = $this->getDoctrine()->getManager();
        $data = $em->getRepository('StoCoreBundle:Country')->findAll();

        return new Response($serializer->serialize($data, 'json'));

    }

    /**
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Update Country",
     *  statusCodes={
     *         200="Returned when successful"
     *         }
     * )
     * @Rest\View
     * @Route("/api/country/", name="api_country_update")
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
     *  description="Delete Country",
     *  statusCodes={
     *         200="Returned when successful"
     *         }
     * )
     *
     * @param  integer $id
     *
     * @Rest\View
     * @Route("/api/country/", name="api_country_delete", requirements={"id" = "\d+"} )
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
