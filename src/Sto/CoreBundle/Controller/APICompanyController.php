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
    public function getCompanies(Request $request)
    {
        $serializer = $this->container->get('jms_serializer');
        $city = $this->get('sto_content.manager.city')->selectedCity();

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
                'search' => trim($request->get('search'))
        ]);

        foreach ($companies as $key => $value) {
            $companies[$key]['specialization_template'] = $this
                ->render('StoContentBundle:Company:specialization_list.html.twig', ['specializations' => $value['specialization']])->getContent();

            $companies[$key]['workingTime_template'] = $this
                ->render('StoContentBundle:Company:workingTime_list.html.twig', ['workingTime' => $value['workingTime']])->getContent();

            $companies[$key]['html'] = $this
                ->render('StoContentBundle:Company:company.html.twig', ['item' => $value])->getContent();
        }

        return new Response($serializer->serialize($companies, 'json'));
    }
}
