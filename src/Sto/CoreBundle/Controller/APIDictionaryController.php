<?php
namespace Sto\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sto\CoreBundle\Entity\Dictionary;

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
     * @Route("/api/dictionary/company_types", name="api_dictionary_company_types", options={"expose"=true})
     * @Method({"GET"})
     */
    public function getCompanyTypesAction()
    {
        $serializer = $this->container->get('jms_serializer');

        $em = $this->getDoctrine()->getManager();
        $data = $em->getRepository('StoCoreBundle:Dictionary\Company')
            ->createQueryBuilder('c')
            ->select('c.id, c.name')
            ->where('c.parent is null')
            ->getQuery()
            ->getArrayResult()
        ;

        return new Response($serializer->serialize($data, 'json'));
    }

    /**
     * @Rest\View
     * @Route("/api/dictionary/sub_company_types_for/{id}", name="api_dictionary_sub_company_types", options={"expose"=true})
     * @Method({"GET"})
     */
    public function getSubCompanyTypesListAction($id)
    {
        $serializer = $this->container->get('jms_serializer');

        $em = $this->getDoctrine()->getManager();
        $data = $em->getRepository('StoCoreBundle:Dictionary\Company')
            ->createQueryBuilder('c')
            ->select('c.id, c.name')
            ->where('c.parent = :companyType')
            ->getQuery()
            ->setParameter('companyType', $id)
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

    /**
     * @ApiDoc(
     *  description="Получить список Типов контактов",
     *  statusCodes={
     *         200="Returned when successful"
     *         }
     * )
     * @return List Of Dictionarioes
     *
     * @Rest\View
     * @Route("/api/dictionary/contact_types", name="api_dictionary_contact_types", options={"expose"=true})
     * @Method({"GET"})
     */
    public function getContactTypesAction()
    {
        $serializer = $this->container->get('jms_serializer');

        $em = $this->getDoctrine()->getManager();
        $data = $em->getRepository('StoCoreBundle:Dictionary\ContactType')
            ->createQueryBuilder('c')
            ->select('c.id, c.prefix, c.name')
            ->getQuery()
            ->getArrayResult()
        ;

        return new Response($serializer->serialize($data, 'json'));
    }
}
