<?php
namespace Sto\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Serializer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sto\UserBundle\Entity\User;
use Sto\UserBundle\Entity\Contacts;

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
     * @Route("/api/user/login", name="api_user_login", options={"expose"=true})
     * @Method({"POST"})
     */
    public function loginAction(Request $request)
    {
        $errorFlag = false;
        $serializer = $this->container->get('jms_serializer');
        $user = null;

        if ($request->get('_username') && $request->get('_password')) {
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository('StoUserBundle:User')
                ->findUserByNameOrByEmail($request->get('_username'));
            if(!$user) {
                $errorFlag = true;
            } else {
                $encoder = $this->container
                    ->get('security.encoder_factory')
                    ->getEncoder($user)
                ;
                if (!($user->getPassword()==$encoder->encodePassword($request->get('_password'), $user->getSalt()))) {
                    $errorFlag = true;
                }
            }
        } else {
            $errorFlag = true;
        }

        if (!$errorFlag) {
            $this->get('sto.user.authenticate')->authenticate($user);
        }

        $response = new Response($serializer->serialize(array("success" => !$errorFlag), 'json'), 200);
        $response->headers->set('Content-Type',' application-json; charset=utf8');
        return $response;
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

    /**
     * @ApiDoc(
     *  description="save Personal User Data",
     *  statusCodes={
     *         200="Returned when successful"
     *         }
     * )
     * @Rest\View
     * @Route("/api/user/save/personal", name="api_user_save_personal")
     * @Method({"GET"})
     */
    public function savePersonalAction(Request $request)
    {
        $serializer = $this->container->get('jms_serializer');

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('StoUserBundle:User')->findOneById($this->getUser()->getId());
        if (!$user)
            return new Response($serializer->serialize(array("message" => "Not found User", "type" => "error", "code" => 404), 'json'), 404);

        $user->setFirstName($request->get('firstName'));
        $user->setLastName($request->get('lastName'));
        if ($request->get('lastName')!='')
            $user->setUsername($request->get('userName'));
        $user->setBirthDate(new \DateTime($request->get('birthDate')));
        if ($request->get('city')>0) {
            $city = $em->getRepository('StoCoreBundle:Country')->findOneById($request->get('city'));
            if ($city) {
                $user->setCity($city);
            }
        }
        $em->persist($user);
        $em->flush();

        $data = $em->createQueryBuilder()
            ->select('b.username, b.email,  b.id,  b.firstName,  b.lastName,  b.phoneNumber,  b.gender,  c.name as city, c.id as city_id')
            ->from('StoUserBundle:User', 'b')
            ->join('b.city', 'c')
            ->where('b.id = :id')
            ->setParameter('id', $this->getUser()->getId())
            ->getQuery()
            ->getArrayResult()
        ;

        if ($data === NULL)
            return new Response($serializer->serialize(["message" => "Not found User", "type" => "error", "code" => 404], 'json'), 404);
        else {
            $data[0]['years'] = $user->getYears();
            $data[0]['birthDate'] = $request->get('birthDate');

            return new Response($serializer->serialize($data, 'json'));
        }
    }

     /**
      * @ApiDoc(
      *  description="Edit Description in User profile",
      *  statusCodes={
      *         200="Returned when successful"
      *         }
      * )
      * @Rest\View
      * @Route("/api/edit/description", name="api_edit_user_description")
      * @Method({"POST"})
      */
    public function editDescriptionAction(Request $request)
    {
        $serializer = $this->container->get('jms_serializer');

        $field = ucfirst($request->get('name'));
        $value = $request->get('value');
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('StoUserBundle:User')->findOneById($this->getUser()->getId());
        if (method_exists($user, 'set'.$field)) {
            $user->{'set'.$field}($value);
        } else {
            return new Response($serializer->serialize(['message' => 'User doesn\'t have field '.$field, "type" => "error", "code"=>404], 'json'), 404);
        }
        $em->persist($user);
        $em->flush();

        $data[$field] = $value;

        return new Response($serializer->serialize($data, 'json'));
    }

    /**
     * @ApiDoc(
     *  description="save User Contacts",
     *  statusCodes={
     *         200="Returned when successful"
     *         }
     * )
     * @Rest\View
     * @Route("/api/user/save/contacts", name="api_user_save_contacts")
     * @Method({"GET"})
     */
    public function saveContactsAction(Request $request)
    {
        $serializer = $this->container->get('jms_serializer');

        $contacts = $request->get('contact');

        $em = $this->getDoctrine()->getManager();
        if ($contacts) {
        foreach ($contacts as $contact_id => $value) {
            $contact = $em->getRepository('StoUserBundle:Contacts')->findOneById($contact_id);
            if ($contact) {
                $contact->setValue($value['value']);
                $type = $em->getRepository('StoCoreBundle:Dictionary\ContactType')->findOneById($value['type']);
                if ($type) {
                    $contact->setType($type);
                }
                $em->persist($contact);
            }
        }
        }
        if ($request->get('add')) {
            $new_contact = new Contacts();
            $new_contact->setValue($request->get('add'));
            $type = $em->getRepository('StoCoreBundle:Dictionary\ContactType')->findOneById($request->get('type-add'));
                if ($type) {
                    $new_contact->setType($type);
                }
            $new_contact->setUser($this->get('security.context')->getToken()->getUser());
            $em->persist($new_contact);

        }

        $em->flush();

        $data = $em->createQueryBuilder()
            ->select('b.id,  b.value, t.id as type_id, t.name, t.prefix')
            ->from('StoUserBundle:Contacts', 'b')
            ->join('b.type', 't')
            ->join('b.user', 'u')
            ->where('u.id = :id')
            ->setParameter('id', $this->getUser()->getId())
            ->getQuery()
            ->getArrayResult()
        ;

        return new Response($serializer->serialize($data, 'json'));
    }

    /**
     * @ApiDoc(
     *  description="Remove User Contact",
     *  statusCodes={
     *         200="Returned when successful"
     *         }
     * )
     * @Rest\View
     * @Route("/api/user/remove/contact", name="api_user_remove_contact")
     * @Method({"GET"})
     */
    public function removeContactAction(Request $request)
    {
        $serializer = $this->container->get('jms_serializer');

        $id = $request->get('id');

        $em = $this->getDoctrine()->getManager();
        $contact = $em->getRepository('StoUserBundle:Contacts')->findOneById($id);
        if (!$contact)
            return new Response($serializer->serialize(array("message" => "Not found Contact", "type" => "error", "code" => 404), 'json'), 404);
        $em->remove($contact);
        $em->flush();

        return new Response($serializer->serialize(array("message" => "Contact removed", "code" => 200), 'json'), 200);
    }

}
