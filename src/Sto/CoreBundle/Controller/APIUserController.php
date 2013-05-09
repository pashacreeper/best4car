<?php
namespace Sto\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sto\UserBundle\Entity\User;

/**
 * User controller.
 */
class APIUserController extends FOSRestController
{
    /**
     * @ApiDoc(
     *  description="Get User By Id",
     *  statusCodes={
     *         200="Returned when successful",
     *         404="Returned when the User is not found"}
     * )
     *
     * @param  integer $id
     * @return User
     *
     * @Rest\View
     * @Route("/api/user/{id}", name="api_user_get", requirements={"id" = "\d+"} )
     * @Method({"GET"})
     */
    public function getAction($id)
    {
        $serializer = $this->container->get('jms_serializer');

        $em = $this->getDoctrine()->getManager();
        $data = $em->createQueryBuilder()
            ->select('b.username,  b.email,  b.roles,  b.id,  b.firstName,  b.lastName,  b.rating,  b.ratingGroupId,  b.phoneNumber,  b.gender,  b.city,  b.linkGarage')
            ->from('StoUserBundle:User', 'b')
            ->where('b.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getArrayResult()
        ;

        if ($data === NULL)
            return new Response($serializer->serialize(["message" => "Not found User", "type" => "error", "code" => 404], 'json'), 404);
        else
            return new Response($serializer->serialize($data, 'json'));
    }

    /**
     * @ApiDoc(
     *  resource=true,
     *  description="Create User",
     *  statusCodes={
     *         200="Returned when successful"
     *         }
     * )
     * @Rest\View
     * @Route("/api/user/", name="api_user_add")
     * @Method({"POST"})
     */
    public function addAction()
    {
        $serializer = $this->container->get('jms_serializer');

        return new Response($serializer->serialize(array("message" => "Permission denied", "type" => "error", "code" => 403), 'json'), 403);
    }

    /**
     * @ApiDoc(
     *  resource=true,
     *  description="Update User",
     *  statusCodes={
     *         200="Returned when successful"
     *         }
     * )
     * @Rest\View
     * @Route("/api/user/", name="api_user_update")
     * @Method({"PUT"})
     */
    public function updateAction()
    {
        $serializer = $this->container->get('jms_serializer');

        return new Response($serializer->serialize(array("message" => "Permission denied", "type" => "error", "code" => 403), 'json'), 403);
    }

    /**
     * @ApiDoc(
     *  description="Login User",
     *  statusCodes={
     *         200="Returned when successful",
     *         400="Invalid username or password combination"}
     * )
     * @Rest\View
     * @Route("/api/user/login", name="api_user_login")
     * @Method({"POST"})
     */
    public function loginAction()
    {
        $serializer = $this->container->get('jms_serializer');

        return new Response($serializer->serialize(array("message" => "Permission denied", "type" => "error", "code" => 403), 'json'), 403);
    }

    /**
     * @ApiDoc(
     *  description="Logout User",
     *  statusCodes={
     *         200="Returned when successful"
     *         }
     * )
     * @Rest\View
     * @Route("/api/user/logout", name="api_user_logout")
     * @Method({"GET"})
     */
    public function logoutAction()
    {
        $serializer = $this->container->get('jms_serializer');

        return new Response($serializer->serialize(array("message" => "Permission denied", "type" => "error", "code" => 403), 'json'), 403);
    }
}
