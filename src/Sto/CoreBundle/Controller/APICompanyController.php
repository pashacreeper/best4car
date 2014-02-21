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
     */
    public function getCompanies(Request $request)
    {
        $serializer = $this->container->get('jms_serializer');
        $city = $this->get('sto.service.city_manager')->selectedCity();

        $form = $this->createForm(new AdvancedSearchType());
        $form->bind($request);
        $formData = $form->getData();

        $additionalServices = array_keys($request->query->get('additional_services', []));

        $companies = $this->getDoctrine()
            ->getManager()
            ->getRepository('StoCoreBundle:Company')
            ->getCompaniesWithFilter([
                'city' => $city->getid(),
                'companyType' => $formData["companyType"],
                'subCompanyType' => $formData["subCompanyType"],
                'auto' => $formData["auto"],
                'rating' => $request->get('rating'),
                'additionalServices' => $additionalServices,
                'deals' => $request->get('deals'),
                'sort' => $request->get('sort'),
                'search' => trim($request->get('search')),
                'time' => array_keys($request->get('time', []))
            ])
        ;

        foreach ($companies as $key => $company) {
            $companies[$key]['rating'] = ($companies[$key]['rating'] !== null)
                ? number_format($companies[$key]['rating'], 1)
                : 'n/a'
            ;

            $companies[$key]['cached_logo'] = null;
            if ($company['logoName']) {
                $companies[$key]['cached_logo'] = $this->container->get('liip_imagine.cache.manager')
                    ->getBrowserPath(
                        "/".$this->container->getParameter('storage_path')."/company_logo/".$company['logoName'],
                        'company_logo_card_filter'
                    )
                ;
            }

            $company['activeDeals'] = $companies[$key]['activeDeals'] = 0;
            if (!empty($company['deals'])) {
                $em = $this->getDoctrine()->getManager();
                $company['activeDeals'] = $companies[$key]['activeDeals'] = $em->getRepository('StoCoreBundle:Deal')
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

            $companies[$key]['specialization_template'] = $this->render(
                'StoContentBundle:Company:specialization_list.html.twig',
                [
                    'specializations' => $company['specializations'],
                    'additionalServices' => $company['additionalServices']
                ]
            )->getContent();

            $companies[$key]['workingTime_template'] = $this->render(
                'StoContentBundle:Company:workingTime_list.html.twig',
                ['workingTime' => $company['workingTime']]
            )->getContent();

            $companies[$key]['html'] = $this->render(
                'StoContentBundle:Company:company.html.twig',
                ['item' => $company]
            )->getContent();
        }

        return new Response($serializer->serialize($companies, 'json'));
    }
}
