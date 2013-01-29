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
use Sto\UserBundle\Entity\User;



/**
 * User controller.
 *
 */
class APIUserController extends FOSRestController
{
    /**
     *
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

        $serializer = $this->container->get('serializer');
        $data = $this->getDoctrine()->getManager()->getRepository('StoUserBundle:User')->find($id);

        if ($data === NULL)
            return new Response($serializer->serialize(array("message" => "Not found user", "type" => "error", "code" => 404, ), 'json'), 404);
        else
            return new Response($serializer->serialize($data, 'json'));

    }

    /**
     *
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
        $serializer = $this->container->get('serializer');
        // hardcoded "Coming Soon"
        return new Response($serializer->serialize(array("message" => "Permission denied", "type" => "error", "code" => 403), 'json'), 403);
    }

    /**
     *
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
        $serializer = $this->container->get('serializer');
        // hardcoded "Coming Soon"
        return new Response($serializer->serialize(array("message" => "Permission denied", "type" => "error", "code" => 403), 'json'), 403);
    }

    /**
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Delete User",
     *  statusCodes={
     *         200="Returned when successful"
     *         }
     * )
     *
     *
     * @param  integer $id
     * @return User
     *
     * @Rest\View
     * @Route("/api/user/", name="api_user_delete", requirements={"id" = "\d+"} )
     *
     * @Method({"DELETE"})
     */
    public function deleteAction()
    {
        $serializer = $this->container->get('serializer');
        // hardcoded "Coming Soon"
        return new Response($serializer->serialize(array("message" => "Permission denied", "type" => "error", "code" => 403), 'json'), 403);
    }


    /**
     *
     * @ApiDoc(
     *  description="Login User",
     *  statusCodes={
     *         200="Returned when successful",
     *         400="Invalid username or password combination"}
     * )
     *
     * @Rest\View
     * @Route("/api/user/login", name="api_user_login")
     * @Method({"POST"})
     */

    public function loginAction()
    {

        $serializer = $this->container->get('serializer');
        // hardcoded "Coming Soon"
        return new Response($serializer->serialize(array("message" => "Permission denied", "type" => "error", "code" => 403), 'json'), 403);

    }

    /**
     *
     * @ApiDoc(
     *  description="Logout User",
     *  statusCodes={
     *         200="Returned when successful"
     *         }
     * )
     *
     * @Rest\View
     * @Route("/api/user/logout", name="api_user_logout")
     * @Method({"GET"})
     */

    public function logoutAction()
    {

        $serializer = $this->container->get('serializer');
        // hardcoded "Coming Soon"
        return new Response($serializer->serialize(array("message" => "Permission denied", "type" => "error", "code" => 403), 'json'), 403);

    }


}
