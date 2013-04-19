<?php

namespace Sto\CoreBundle\Controller;

use Symfony\Component\Serializer\Serializer,
    Symfony\Component\HttpFoundation\Response,
    // Symfony\Component\HttpFoundation\Request,
    Symfony\Component\HttpKernel\Exception\NotFoundHttpException,
    Symfony\Component\HttpKernel\Exception\HttpException,
    Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\FOSRestController,
    FOS\RestBundle\View\View,
    FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

// use Sto\CoreBundle\Entity\Dictionary\City;

// use Sto\CoreBundle\Entity\Dictionary\Country;
// use Sto\CoreBundle\Entity\DictionaryCity as City;

/**
 * City controller.
 * @Route("/api/city")
 */
class APICityController extends APIBaseController
{

    /**
     *
     * @ApiDoc(
     *  description="Получить город по Id",
     *  statusCodes={
     *         200="Returned when successful",
     *         404="Returned when the City is not found"}
     * )
     *
     * @param  integer $id
     * @return City
     *
     * @Rest\View
     * @Route("/{id}", name="api_city_get", requirements={"id" = "\d+"} )
     * @Method({"GET"})
     */
    public function getAction($id)
    {
        return parent::getAction($id);
    }

    /**
     *
     * @ApiDoc(
     *  description="Получить все города",
     *  statusCodes={
     *         200="Returned when successful"
     *         }
     * )
     *
     * @Rest\View
     * @Route("/all", name="api_city_all")
     * @Method({"GET"})
     */
    public function allAction()
    {
        $serializer = $this->container->get('jms_serializer');

        $em = $this->getDoctrine()->getManager();
        $data = $em->getRepository('StoCoreBundle:Dictionary\Country')
            ->createQueryBuilder('dictionary')
            ->where('dictionary.parent is NOT null')
            ->getQuery()
            ->getArrayResult()
        ;

        return new Response($serializer->serialize($data, 'json'));

    }

    /**
     *
     * @ApiDoc(
     *  description="Получить все города по id страны",
     *  statusCodes={
     *         200="Returned when successful"
     *         }
     * )
     *
     * @param integer $id
     *
     * @Rest\View
     * @Route("/all_by_country/{id}", name="api_city_all_by_country", requirements={"id" = "\d+"} )
     * @Method({"GET"})
     */
    public function allByCountryAction($id)
    {
        $serializer = $this->container->get('jms_serializer');

        $em = $this->getDoctrine()->getManager();
        $data = $em->createQueryBuilder()
            ->select('b')
            ->from('StoCoreBundle:Dictionary\Country', 'b')
            ->where('b.parentId = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getArrayResult()
        ;

        return new Response($serializer->serialize($data, 'json'));
    }

}
