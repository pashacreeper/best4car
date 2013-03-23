<?php

namespace Sto\ContentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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

        $cities = $em->getRepository('StoCoreBundle:DictionaryCity')
            ->createQueryBuilder('city')
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
}
