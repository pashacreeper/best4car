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

use Sto\CoreBundle\Entity\Dictionary;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
/// ------------- EOL --------
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;


/**
 * Dictionary controller.
 *
 */
class APIDictionaryController extends FOSRestController
{

    /**
     *
     * @ApiDoc(
     *  description="Get Dictionary By Id",
     *  statusCodes={
     *         200="Returned when successful",
     *         404="Returned when the Dictionary is not found"}
     * )
     *
     * @param  integer $id
     * @return Dictionary
     *
     * @Rest\View
     * @Route("/api/dictionary/{id}", name="api_dictionary_get", requirements={"id" = "\d+"} )
     * @Method({"GET"})
     */

    public function getAction($id)
    {

        $serializer = $this->container->get('serializer');
        $data = $this->getDoctrine()->getManager()->getRepository('StoCoreBundle:Dictionary')->find($id);

        if ($data === NULL)
            return new Response($serializer->serialize(array("message" => "Not found dictionary", "type" => "error", "code" => 404, ), 'json'), 404);
        else
            return new Response($serializer->serialize($data, 'json'));

    }

    /**
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Create Dictionary",
     *  input="Sto\AdminBundle\Form\DictionaryType",
     *  statusCodes={
     *         200="Returned when successful"
     *         }
     * )
     * @Rest\View
     * @Route("/api/dictionary/", name="api_dictionary_add")
     * @Method({"POST"})
     */
    public function addAction()
    {
        $serializer = $this->container->get('serializer');
        // hardcoded "Coming Soon"
        return new Response($serializer->serialize(array("message" => "Permission denied", "type" => "error", "code" => 403), 'json'), 403);

        $content = $this->get("request")->getContent();

        if (!empty($content)) {
            print_r($content);
            die();
            // try {

                $data = array();
                // $data = $serializer->deserialize($content, 'Sto\CoreBundle\Entity\Dictionary', 'json');
            // } catch (Exception $e) {
            //     return new Response($serializer->serialize(array("message" => $e->message, "type" => "error", "code" => $e->code, ), 'json'), $e->code);
            // }

        }
        else {
            throw $this->createNotFoundException('No content posted');
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($data);
        $em->flush();

        $response = new Response();
        $response->setStatusCode(200);
        // $response->headers->set('Location', $this->generateUrl('get_product', array('id' => $data->getId()), true));

        return $response;

    }

    /**
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Update Dictionary",
     *  input="Sto\AdminBundle\Form\DictionaryType",
     *  statusCodes={
     *         200="Returned when successful"
     *         }
     * )
     * @Rest\View
     * @Route("/api/dictionary/", name="api_dictionary_update")
     * @Method({"PUT"})
     */
    public function updateAction()
    {
        $serializer = $this->container->get('serializer');
        // hardcoded "Coming Soon"
        return new Response($serializer->serialize(array("message" => "Permission denied", "type" => "error", "code" => 403), 'json'), 403);

        $content = $this->get("request")->getContent();
        if (!empty($content)) {
            $data = $serializer->deserialize($content, 'Sto\CoreBundle\Entity\Dictionary', 'json');
        }
        else {
            throw $this->createNotFoundException('No content posted');
        }
        $em = $this->getDoctrine()->getManager();
        $em->persist($data);
        $em->flush();

        // $entity = $em->getRepository('StoCoreBundle:Dictionary')->find($id);

        $response = new Response();
        $response->setStatusCode(201);
        $response->headers->set('Location',
            $this->generateUrl(
                'get_product', array('id' => $data->getId()),
                true // absolute
            )
        );

        return $response;

    }

    /**
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Delete Dictionary",
     *  statusCodes={
     *         200="Returned when successful"
     *         }
     * )
     *
     *
     * @param  integer $id
     * @return Dictionary
     *
     * @Rest\View
     * @Route("/api/dictionary/", name="api_dictionary_delete", requirements={"id" = "\d+"} )
     *
     * @Method({"DELETE"})
     */
    public function deleteAction()
    {
        $serializer = $this->container->get('serializer');
        // hardcoded "Coming Soon"
        return new Response($serializer->serialize(array("message" => "Permission denied", "type" => "error", "code" => 403), 'json'), 403);

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('StoCoreBundle:Dictionary')->find($id);

        if (!$entity) {
            return new Response($serializer->serialize(array("message" => "Not found dictionary", "type" => "error", "code" => 404, ), 'json'), 404);
            // throw $this->createNotFoundException($this->get('translator')->trans('dict.errors.unable_2_find'));
        }

        $em->remove($entity);
        $em->flush();


        $response = new Response();
        $response->setStatusCode(200);

        return $response;

    }


    /**
     *
     * @ApiDoc(
     *  description="Get All Dictionaries",
     *  statusCodes={
     *         200="Returned when successful"
     *         }
     * )
     *
     * @Rest\View
     * @Route("/api/dictionary/all", name="api_dictionary_all")
     * @Method({"GET"})
     */
    public function allAction()
    {
        $serializer = $this->container->get('serializer');

        $em = $this->getDoctrine()->getManager();
        $data = $em->getRepository('StoCoreBundle:Dictionary')->findAll();

        return new Response($serializer->serialize($data, 'json'));

    }

    /**
     *
     * @param  integer $parent_id Parent Id
     * @return List Of Dictionarioes
     *
     *
     * @Rest\View
     * @Route("/api/dictionary/list/{parent_id}", name="api_dictionary_list_by_id", requirements={"parent_id" = "\d+"})
     * @Method({"GET"})
     */
    private function listByParentIdAction($parent_id)
    {
        $serializer = $this->container->get('serializer');
        $em = $this->getDoctrine()->getManager();
        $data = $em->getRepository('StoCoreBundle:Dictionary')
                ->createQueryBuilder('entity')
                ->where('entity.parentId =:filter_parent_id')
                ->setParameter('filter_parent_id', $parent_id)
                // ->orderBy('entity.name')
                ->getQuery()
                ->getResult();

        return new Response($serializer->serialize($data, 'json'));

    }

    /**
     *
     * @ApiDoc(
     *  description="Get List of Services from Dictionary",
     *  statusCodes={
     *         200="Returned when successful"
     *         }
     * )
     * @return List Of Dictionarioes
     *
     * @Rest\View
     * @Route("/api/dictionary/services_list", name="api_dictionary_list_of_services")
     * @Method({"GET"})
     */
    public function getServicesListAction()
    {
        $head_id = $this->container->getParameter('dictionary_services_list_id');
        return $this->listByParentIdAction($head_id);
    }

    /**
     *
     * @ApiDoc(
     *  description="Get List of Company Types from Dictionary",
     *  statusCodes={
     *         200="Returned when successful"
     *         }
     * )
     * @return List Of Dictionarioes
     *
     * @Rest\View
     * @Route("/api/dictionary/company_types_list", name="api_dictionary_list_of_company_types")
     * @Method({"GET"})
     */
    public function getCompanyTypesListAction()
    {
        $head_id = $this->container->getParameter('dictionary_company_types_id');
        return $this->listByParentIdAction($head_id);
    }


    /**
     *
     * @ApiDoc(
     *  description="Get List of currencies from Dictionary",
     *  statusCodes={
     *         200="Returned when successful"
     *         }
     * )
     * @return List Of Dictionarioes
     *
     * @Rest\View
     * @Route("/api/dictionary/currencies_list", name="api_dictionary_list_of_currencies")
     * @Method({"GET"})
     */
    public function getCurrenciesListAction()
    {
        $head_id = $this->container->getParameter('dictionary_currencies_id');
        return $this->listByParentIdAction($head_id);
    }

    /**
     *
     * @ApiDoc(
     *  description="Get List of Additional services from Dictionary",
     *  statusCodes={
     *         200="Returned when successful"
     *         }
     * )
     * @return List Of Dictionarioes
     *
     * @Rest\View
     * @Route("/api/dictionary/additional_services_list", name="api_dictionary_list_of_additional_services")
     * @Method({"GET"})
     */
    public function getAdditionalServicesListAction()
    {
        $head_id = $this->container->getParameter('dictionary_additional_services_id');
        return $this->listByParentIdAction($head_id);
    }

}
