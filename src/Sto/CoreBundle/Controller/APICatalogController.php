<?php

namespace Sto\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sto\CoreBundle\Entity\Catalog;
use Sto\CoreBundle\Entity\Model;

/**
 * APi Auto Catalog controller.
 *
 * @Route("/api/auto/catalog")
 */
class APICatalogController extends FOSRestController
{
    /**
     * @ApiDoc(
     * description="Получить список всех марок автомобилей из автокаталога",
     *  statusCodes={
     *      200="Returned when successful"
     *  }
     * )
     *
     * @Rest\View
     * @Route("/marks", name="api_auto_catalog_get_marks", options={"expose"=true})
     * @Method({"GET"})
     */
    public function getMarks()
    {
        $serializer = $this->container->get('jms_serializer');

        $em = $this->getDoctrine()->getManager();
        $data = $em->getRepository('StoCoreBundle:Mark')
            ->createQueryBuilder('c')
            ->select('c.id, c.name')
            ->getQuery()
            ->getArrayResult()
        ;

        return new Response($serializer->serialize($data, 'json'));
    }

    /**
     * @ApiDoc(
     * description="Получить список всех моделей автомобилей для указанной марки",
     *  statusCodes={
     *      200="Returned when successful",
     *  }
     * )
     *
     * @Rest\View
     * @Route("/mark/{id}/models", name="api_auto_catalog_get_models_for_mark" )
     * @Method({"GET"})
     * @ParamConverter("mark", class="StoCoreBundle:Mark")
     */
    public function getModelsForMark(Mark $mark)
    {
        $serializer = $this->container->get('jms_serializer');

        $em = $this->getDoctrine()->getManager();
        $data = $em->getRepository('StoCoreBundle:Model')
            ->createQueryBuilder('c')
            ->select('c.id, c.name')
            ->where('c.parent = :mark')
            ->getQuery()
            ->setParameter('mark', $mark)
            ->getArrayResult()
        ;

        return new Response($serializer->serialize($data, 'json'));
    }

    /**
     * @ApiDoc(
     * description="Получить список всех модификаций автомобилей для указанной модели",
     *  statusCodes={
     *      200="Returned when successful",
     *  }
     * )
     *
     * @Rest\View
     * @Route("/model/{id}/modifications", name="api_auto_catalog_get_modifications_for_model" )
     * @Method({"GET"})
     * @ParamConverter("model", class="StoCoreBundle:Model")
     */
    public function getModificationForModel(Model $model)
    {
        $serializer = $this->container->get('jms_serializer');

        $em = $this->getDoctrine()->getManager();
        $data = $em->getRepository('StoCoreBundle:Modification')
            ->createQueryBuilder('c')
            ->select('c.id, c.name')
            ->where('c.parent = :model')
            ->getQuery()
            ->setParameter('model', $model)
            ->getArrayResult()
        ;

        return new Response($serializer->serialize($data, 'json'));
    }

    /**
     * @ApiDoc(
     * description="Получить список всех модификаций автомобилей для указанной модели и года",
     *  statusCodes={
     *      200="Returned when successful",
     *  }
     * )
     *
     * @Rest\View
     * @Route("/model/{id}/{year}/modifications", name="api_auto_catalog_get_modifications_for_model_and_year", options={"expose"=true})
     * @Method({"GET"})
     * @ParamConverter("model", class="StoCoreBundle:Model")
     */
    public function getModificationForModelAndYear(Model $model, $year)
    {
        $serializer = $this->container->get('jms_serializer');

        $data = $this->getDoctrine()->getManager()
            ->getRepository('StoCoreBundle:Modification')
            ->findByModelAndYear($model, $year);

        return new Response($serializer->serialize($data, 'json'));
    }
}
