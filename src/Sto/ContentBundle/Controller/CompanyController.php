<?php

namespace Sto\ContentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request,
    Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Method,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Template,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Sto\CoreBundle\Entity\Company,
    Sto\CoreBundle\Entity\FeedbackCompany,
    Sto\ContentBundle\Form\FeedbackCompanyType;

class CompanyController extends Controller
{
    /**
     * @Route("/catalog", name="content_companies")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $repository = $this->getDoctrine()->getManager()->getRepository('StoCoreBundle:Company');
        $query = $repository->createQueryBuilder('company')
            ->select('company, s')
            ->join('company.specialization', 's')
            ->where('company.visible = true')
        ;

        if ($request->isMethod('POST') and $request->get('search')) {
            $words = explode(" ", trim($request->get('search')));

            foreach ($words as $word) {
                $query->andWhere($query->expr()->orx(
                    $query->expr()->like('company.name',':search'),
                    $query->expr()->like('company.fullName',':search'),
                    $query->expr()->like('company.description',':search'),
                    $query->expr()->like('company.slogan',':search'),
                    $query->expr()->like('s.name',':search')
                ))
                ->setParameter('search', '%' . $word . '%');
            }
        }
        $companies = $query
            ->getQuery()
            ->getArrayResult()
        ;

        $cities = $em->getRepository('StoCoreBundle:Dictionary\Country')
            ->createQueryBuilder('city')
            ->where('city.parent is not null')
            ->getQuery()
            ->getArrayResult()
        ;

        return [
            'companies' => json_encode($companies),
            'cities' => $cities,
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
            ->where('company.visible = true')
            ->orderBy('company.rating', 'DESC')
            ->getQuery()
            ->getResult()
        ;

        if (!$companies) {
            return new Responce(500, 'Companies Not found.');
        }

        return [
            'companies' => $companies,
        ];
    }

    /**
     * @Route("/company-feedbacks/{id}", name="company_feedbacks_show")
     * @Method("POST")
     * @Template()
     */
    public function feedbacksAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository('StoCoreBundle:FeedbackCompany')
            ->createQueryBuilder('fc')
            ->where('fc.companyId = :company')
            ->setParameter('company', $id)
            ->getQuery()
        ;

        $feedbacks = $this->get('knp_paginator')->paginate(
            $query,
            $this->get('request')->query->get('page',1),
            3
        );

        return [
            'feedbacks' => $feedbacks,
            'companyId' => $id,
        ];
    }

    /**
     * @Route("/company/{id}/feedback/add", name="content_company_feedbacks_add")
     * @Method("GET")
     * @ParamConverter("company", class="StoCoreBundle:Company")
     * @Template()
     * @Secure(roles="IS_AUTHENTICATED_FULLY")
     */
    public function addFeedbackAction(Company $company)
    {
        $form = $this->createForm(new FeedbackCompanyType, (new FeedbackCompany)->setCompany($company));

        return [
            'form' => $form->createView(),
            'company' => $company
        ];
    }

    /**
     * @Route("/company/{id}/feedback/create", name="content_company_feedbacks_create")
     * @Method("POST")
     * @ParamConverter("company", class="StoCoreBundle:Company")
     * @Template("StoContentBundle:Company:addFeedback.html.twig")
     * @Secure(roles="IS_AUTHENTICATED_FULLY")
     */
    public function createFeedbackAction(Request $request, Company $company)
    {
        $entity = new FeedbackCompany;
        $form = $this->createForm(new FeedbackCompanyType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->setUser($this->getUser())
                ->setCompany($company)
                ->setPluses(0)
                ->setMinuses(0)
                ->setPublished(false)
                ->setIp($request->getClientIp())
            ;
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('content_company_show', array('id' => $company->getId())));
        }

        return array(
            'form'   => $form->createView(),
            'company' => $company
        );
    }
}
