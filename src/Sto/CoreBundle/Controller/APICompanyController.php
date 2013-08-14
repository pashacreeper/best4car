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
use Sto\ContentBundle\Form\Extension\ChoiceList\WorkTime;
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
        $city = $this->get('sto_content.manager.city')->selectedCity();

        $responseType = $request->get('responce-type', 'json');

        $form = $this->createForm(new AdvancedSearchType());
        $form->bind($request);
        $formData = $form->getData();
        $companyType = $formData["companyType"];
        $subCompanyType = $formData["subCompanyType"];
        $auto = $formData["auto"];

        $rating = $request->get('rating');

        $filter = [];
        $timing = [];

        $workingTime = new WorkTime();
        foreach ($workingTime->getChoices() as $element) {
            $timing[$element] = $request->get($element);
        }

        $additionalServices = new AdditionalServices();
        foreach ($additionalServices->getChoices() as $element) {
            $filter[$element] = $request->get($element);
        }

        $deals = $request->get('deals');
        $sort = $request->get('sort');

        $companies = $this->getDoctrine()
            ->getManager()
            ->getRepository('StoCoreBundle:Company')
            ->getCompaniesWithFilter($city, $companyType, $subCompanyType, $auto, $rating, $filter, $deals, $timing, $sort)
        ;

        if ($responseType == 'html') {
            if (!$companies) {
                return new Response('Companies Not found.', 500);
            }

            return new Response($this->renderView('StoContentBundle:Company:getAllAjax.html.twig',
                [
                    'companies' => $companies
                ])
            );
        } elseif ($responseType == 'json') {
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
