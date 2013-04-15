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
use Sto\CoreBundle\Entity\Dictionary;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

// use Sto\CoreBundle\Entity\Dictionary;


/**
 * Dictionary controller.
 */
class APIDictionaryController extends APIBaseController
{
    /**
     * @ApiDoc(
     *  description="Получить словарь по Id",
     *  statusCodes={
     *         200="Returned when successful",
     *         404="Returned when the Dictionary is not found"}
     * )
     *
     * @param  integer    $id
     * @return Dictionary
     *
     * @Rest\View
     * @Route("/api/dictionary/{id}", name="api_dictionary_get", requirements={"id" = "\d+"} )
     * @Method({"GET"})
     */
    public function getAction($id)
    {
        return parent::getAction($id);
    }

    /**
     * @ApiDoc(
     *  description="Получить список Акций",
     *  statusCodes={
     *         200="Returned when successful"
     *         }
     * )
     * @return List Of Deals
     *
     * @Rest\View
     * @Route("/api/dictionary/deal_list", name="api_dictionary_list_of_deals")
     * @Method({"GET"})
     */
    public function getDealListAction()
    {
        $serializer = $this->container->get('jms_serializer');

        $em = $this->getDoctrine()->getManager();
        $data = $em->getRepository('StoCoreBundle:Dictionary\Deal')
            ->createQueryBuilder('d')
            ->select('d.id, d.name')
            ->getQuery()
            ->getArrayResult()
        ;

        return new Response($serializer->serialize($data, 'json'));
    }

    /**
     * @ApiDoc(
     *  description="Получить список Типов работ",
     *  statusCodes={
     *         200="Returned when successful"
     *         }
     * )
     * @return List Of Works
     *
     * @Rest\View
     * @Route("/api/dictionary/work_list", name="api_dictionary_list_of_works")
     * @Method({"GET"})
     */
    public function getWorkListAction()
    {
        $serializer = $this->container->get('jms_serializer');

        $em = $this->getDoctrine()->getManager();
        $data = $em->getRepository('StoCoreBundle:Dictionary\Work')
            ->createQueryBuilder('w')
            ->select('w.id, w.name')
            ->getQuery()
            ->getArrayResult()
        ;

        return new Response($serializer->serialize($data, 'json'));
    }

    /**
     * @ApiDoc(
     *  description="Получить список Типов компаний",
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
        $serializer = $this->container->get('jms_serializer');

        $em = $this->getDoctrine()->getManager();
        $data = $em->getRepository('StoCoreBundle:Dictionary\Company')
            ->createQueryBuilder('c')
            ->select('c.id, c.name')
            ->getQuery()
            ->getArrayResult()
        ;

        return new Response($serializer->serialize($data, 'json'));
    }

    /**
     * @ApiDoc(
     *  description="Получить список Валют",
     *  statusCodes={
     *         200="Returned when successful"
     *         }
     * )
     * @return List Of currencies_list
     *
     * @Rest\View
     * @Route("/api/dictionary/currencies_list", name="api_dictionary_list_of_currencies")
     * @Method({"GET"})
     */
    public function getCurrenciesListAction()
    {
        $serializer = $this->container->get('jms_serializer');

        $em = $this->getDoctrine()->getManager();
        $data = $em->getRepository('StoCoreBundle:Dictionary\Currency')
            ->createQueryBuilder('c')
            ->select('c.id, c.name, c.shortName')
            ->getQuery()
            ->getArrayResult()
        ;

        return new Response($serializer->serialize($data, 'json'));
    }

    /**
     * @ApiDoc(
     *  description="Получить список Дополнительных услуг",
     *  statusCodes={
     *         200="Returned when successful"
     *         }
     * )
     * @return List Of additional_services_list
     *
     * @Rest\View
     * @Route("/api/dictionary/additional_services_list", name="api_dictionary_list_of_additional_services")
     * @Method({"GET"})
     */
    public function getAdditionalServicesListAction()
    {
        $serializer = $this->container->get('jms_serializer');

        $em = $this->getDoctrine()->getManager();
        $data = $em->getRepository('StoCoreBundle:Dictionary\AdditionalService')
            ->createQueryBuilder('ac')
            ->select('ac.id, ac.name')
            ->getQuery()
            ->getArrayResult()
        ;

        return new Response($serializer->serialize($data, 'json'));
    }
}
