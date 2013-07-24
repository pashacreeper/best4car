<?php

namespace Sto\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sto\CoreBundle\Entity\Catalog;

/**
 * APi Auto Catalog controller.
 *
 * @Route("/api/company")
 */
class APICompanyController extends FOSRestController
{
    /**
     * @ApiDoc(
     * description="Получить список всех компаний",
     *     statusCodes={
     *         200="Returned when successful"
     *     }
     * )
     *
     * @Rest\View
     * @Route("/", name="api_get_companies", options={"expose"=true})
     * @Method({"GET"})
     */
    public function getCompanies()
    {
        $serializer = $this->container->get('jms_serializer');
        $city = $this->get('sto_content.manager.city')->selectedCity();

        $em = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getManager()->getRepository('StoCoreBundle:Company');
        $query = $repository->createQueryBuilder('company')
            ->select('company, s, d')
            ->join('company.specialization', 's')
            ->leftJoin('company.deals', 'd')
            ->where('company.visible = true')
            ->andWhere('company.cityId = :city')
            ->setParameter('city', $city->getId())
        ;

        $companies = $query
            ->getQuery()
            ->getArrayResult()
        ;

        foreach ($companies as $key => $value) {
            $companies[$key]['specialization_template'] = $this->render(
                    'StoContentBundle:Company:specialization_list.html.twig',
                    ['specializations' => $value['specialization']]
                )->getContent()
            ;

            $companies[$key]['workingTime_template'] = $this->render(
                    'StoContentBundle:Company:workingTime_list.html.twig',
                    ['workingTime' => $value['workingTime']]
                )->getContent()
            ;

            $companies[$key]['specialDeal'] = $this->render(
                    'StoContentBundle:Company:specialDealInBallon.html.twig',
                    ['deals' => $value['deals']]
                )->getContent()
            ;
        }

        return new Response($serializer->serialize($companies, 'json'));
    }

    /**
     * @ApiDoc(
     * description="Получить список всех моделей автомобилей для указанной марки",
     *     statusCodes={
     *         200="Returned when successful",
     *     }
     * )
     *
     * @Rest\View
     * @Route("/filter", name="api_auto_get_companies_with_filter", options={"expose"=true})
     */
    public function getCompaniesWithFilter(Request $request)
    {
        $serializer = $this->container->get('jms_serializer');

        $responce_type = ($request->get('responce-type')) ? ($request->get('responce-type')) : 'json';
        $companyType = ($request->get('company_type')) ? ($request->get('company_type')) : null;
        $subComppanyType = ($request->get('sub_company_type')) ? ($request->get('sub_company_type')) : null;

        $city = $this->get('sto_content.manager.city')->selectedCity();

        $auto = ($request->get('marks')) ? ($request->get('marks')) : null;
        $rating = ($request->get('rating')) ? ($request->get('rating')) : null;
        $filter = []; $timing = [];
        $timing['24hours'] = ($request->get('24hours')) ? ($request->get('24hours')) : null;
        $timing['late'] = ($request->get('late')) ? ($request->get('late')) : null;
        $timing['weekends'] = ($request->get('weekends')) ? ($request->get('weekends')) : null;

        $filter['evaqu'] = ($request->get('evaquate')) ? ($request->get('evaquate')) : null;
        $filter['wifi'] = ($request->get('wifi')) ? ($request->get('wifi')) : null;
        $filter['waiti'] = ($request->get('tv')) ? ($request->get('tv')) : null;
        $filter['coffe'] = ($request->get('coffee')) ? ($request->get('coffee')) : null;
        $filter['resta'] = ($request->get('restaurant')) ? ($request->get('restaurant')) : null;
        $filter['credi'] = ($request->get('credit-card')) ? ($request->get('credit-card')) : null;

        $deals = ($request->get('deals')) ? ($request->get('deals')) : null;

        $companies = $this->getDoctrine()->getManager()->getRepository('StoCoreBundle:Company')->getCompaniesWithFilter($city, $companyType, $subComppanyType, $auto, $rating, $filter, $deals, $timing);

        if ($responce_type=='html') {

            if (!$companies) {
                return new Response('Companies Not found.', 500);
            }

            return new Response($this->renderView('StoContentBundle:Company:getAllAjax.html.twig',
                [
                    'companies' => $companies
                ])
            );
        } elseif ($responce_type=='json') {

            foreach ($companies as $key => $value) {
                $companies[$key]['specialization_template'] = $this
                    ->render('StoContentBundle:Company:specialization_list.html.twig', ['specializations' => $value['specialization']])->getContent()
                ;

                $companies[$key]['workingTime_template'] = $this
                    ->render('StoContentBundle:Company:workingTime_list.html.twig', ['workingTime' => $value['workingTime']])->getContent()
                ;
            }

            return new Response($serializer->serialize($companies, 'json'));
        }
    }
}
