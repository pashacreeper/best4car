<?php

namespace Sto\ContentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request,
    Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Method,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Template,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sto\CoreBundle\Entity\Company;

class CompanyController extends Controller
{
    /**
     * @Route("/companies", name="content_companies")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $companies = $em->getRepository('StoCoreBundle:Company')
            ->createQueryBuilder('company')
            ->getQuery()
            ->getArrayResult()
        ;

        $cities = $em->getRepository('StoCoreBundle:Dictionary\Country')
            ->createQueryBuilder('city')
            ->where('city.parent is not null')
            ->getQuery()
            ->getArrayResult();

        return [
            'companies' => json_encode($companies),
            'cities' => $cities
        ];
    }

    /**
     * @Route("/company/{id}", name="content_company_show")
     * @Method("GET")
     * @Template()
     * @ParamConverter("company", class="StoCoreBundle:Company")
     */
    public function showAction(Company $company)
    {
        return [
            'company' => $company
        ];
    }

    /**
     * Ajax get companies
     *
     * @Route("/ajax/getall", name="company_ajax_get_all")
     * @Template()
     */
    public function getAllAjaxAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $companies = $em->getRepository('StoCoreBundle:Company')
            ->createQueryBuilder('company')
            ->getQuery()
            ->getArrayResult();

        if (!$companies)
            return new Responce(500, 'Companies Not found.');

        return [
            'companies' => $companies,
        ];
    }

    /**
     * Ajax get companies
     *
     * @Route("/ajax/get-company-types", name="copmpany_ajax_get_search_comopany_types")
     */
    public function getSearchCompanyTypesAjaxAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $companies = $em->getRepository('StoCoreBundle:Dictionary\Company')
            ->createQueryBuilder('company')
            ->where('company.parent is null')
            ->getQuery()
            ->getArrayResult();

        if (!$companies)
            return new Response(500, 'Companies Not found.');

        $response = new Response(json_encode(array('companies' => $companies)));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * Ajax get companies
     *
     * @Route("/ajax/get-company-subtypes", name="company_ajax_get_company_subtypes")
     */
    public function getSearchCompanySubtypesAjaxAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $companies = $em->getRepository('StoCoreBundle:Company')->find($request->get('type'));

        $companies = $em->getRepository('StoCoreBundle:Dictionary\Company')
            ->createQueryBuilder('company')
            ->where('company.parentId = :type')
            ->setParameter('type', $request->get('type'))
            ->getQuery()
            ->getArrayResult();

        if (!$companies)
            return new Response(500, 'Subtype Not found.');

        $response = new Response(json_encode(array('subtypes' => $companies)));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
