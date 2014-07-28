<?php

namespace Sto\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sto\CoreBundle\Entity\Catalog;
use Sto\ContentBundle\Form\Extension\ChoiceList\AdditionalServices;
use Sto\ContentBundle\Form\AdvancedSearchType;
use Sto\CoreBundle\Entity\Company;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * APi Auto Catalog controller.
 *
 * @Route("/api/company")
 */
class APICompanyController extends FOSRestController
{
    /**
     * @ApiDoc(
     * description="Получить список всех компаний для карты",
     *     statusCodes={
     *         200="Returned when successful"
     *     }
     * )
     *
     * @Rest\View
     * @Route("/", name="api_get_companies", options={"expose"=true})
     */
    public function getCompanies(Request $request)
    {
        $city = $this->get('sto_content.manager.city')->selectedCity();

        $form = $this->createForm(new AdvancedSearchType());
        $form->bind($request);
        $formData = $form->getData();

        $additionalServices = array_keys($request->query->get('additional_services', []));

        $subCompanyType = isset($formData["subCompanyType"]) ? $formData["subCompanyType"] : null;

        $companies = $this->getDoctrine()
            ->getManager()
            ->getRepository('StoCoreBundle:Company')
            ->getCompaniesWithFilterForMap([
                'city' => $city->getid(),
                'companyType' => $formData["companyType"],
                'subCompanyType' => $subCompanyType,
                'auto' => $formData["auto"],
                'rating' => $request->get('rating'),
                'additionalServices' => $additionalServices,
                'deals' => $request->get('deals'),
                'sort' => $request->get('sort'),
                'search' => trim($request->get('search')),
                'time' => array_keys($request->get('time', []))
            ])
        ;

        $returnCompanies = [];
        foreach ($companies as $key => $company) {
            $newCompany = [];
            $newCompany['id'] = $company['id'];
            $newCompany['n'] = $company['name'];
            $newCompany['g'] = $company['gps'];
            $newCompany['v'] = $company['vip'];
            $newCompany['t'] = $company['type'];

            $returnCompanies[$key] = $newCompany;
        }

        return new Response(json_encode($returnCompanies));
    }

    /**
     * @ApiDoc(
     * description="Получить список всех компаний для списка",
     *     statusCodes={
     *         200="Returned when successful"
     *     }
     * )
     *
     * @Rest\View
     * @Route("/list", name="api_get_companies_list", options={"expose"=true})
     */
    public function getCompaniesList(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $serializer = $this->container->get('jms_serializer');
        $city = $this->get('sto_content.manager.city')->selectedCity();

        $form = $this->createForm(new AdvancedSearchType());
        $form->bind($request);
        $formData = $form->getData();

        $additionalServices = array_keys($request->query->get('additional_services', []));

        $subCompanyType = isset($formData["subCompanyType"]) ? $formData["subCompanyType"] : null;

        $companies = $em
            ->getRepository('StoCoreBundle:Company')
            ->getCompaniesWithFilterForList([
                'city' => $city->getid(),
                'companyType' => $formData["companyType"],
                'subCompanyType' => $subCompanyType,
                'auto' => $formData["auto"],
                'rating' => $request->get('rating'),
                'additionalServices' => $additionalServices,
                'deals' => $request->get('deals'),
                'sort' => $request->get('sort'),
                'search' => trim($request->get('search')),
                'time' => array_keys($request->get('time', []))
            ])
        ;

        $returnCompanies = [];
        foreach ($companies as $key => $company) {
            $newCompany = [];
            $newCompany['id'] = $company['id'];

            $newCompany['activeDeals'] = $company['activeDeals'] = 0;
            if (!empty($company['deals'])) {
                $newCompany['activeDeals'] = $company['activeDeals'] = $em->getRepository('StoCoreBundle:Deal')
                    ->getActiveDaelsCountByCompany($company['id']);
            }

            foreach ($company['workingTime'] as $wtKey => $wtValue) {
                $company['workingTime'][$wtKey]['days'] = [
                    $wtValue['daysMonday'],
                    $wtValue['daysTuesday'],
                    $wtValue['daysWednesday'],
                    $wtValue['daysThursday'],
                    $wtValue['daysFriday'],
                    $wtValue['daysSaturday'],
                    $wtValue['daysSunday']
                ];
            }

            $newCompany['html'] = $this->render(
                'StoContentBundle:Company:company.html.twig',
                ['item' => $company]
            )->getContent();

            $returnCompanies[$key] = $newCompany;
        }

        return new JsonResponse($returnCompanies);
    }

    /**
     * @ApiDoc(
     * description="Получить балун для карт",
     *     statusCodes={
     *         200="Returned when successful"
     *     }
     * )
     *
     * @Template()
     * @Route("/{id}/balloon/", name="api_get_company_balloon", options={"expose"=true})
     * @ParamConverter("company", class="StoCoreBundle:Company")
     */
    public function companyBalloonAction(Request $request, Company $company)
    {
        $em = $this->getDoctrine()->getManager();
        $activeDeals = $em->getRepository('StoCoreBundle:Deal')->getActiveDaelsCountByCompany($company);

        return [
            'company' => $company,
            'activeDeals' => $activeDeals,
        ];
    }
}
