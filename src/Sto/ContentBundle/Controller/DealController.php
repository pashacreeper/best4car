<?php

namespace Sto\ContentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Template,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sto\CoreBundle\Entity\Deal;

class DealController extends Controller
{
    /**
     * @Route("/deals", name="content_deals")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('StoCoreBundle:Deal');
        $query = $repository->createQueryBuilder('deal')
            ->where('deal.endDate > :endDate')
            ->setParameter('endDate', new \DateTime('now'));
        ;

        if ($request->get('search')) {
            $query->andWhere($query->expr()->orx(
                $query->expr()->like('deal.name',':search'),
                $query->expr()->like('deal.description',':search'),
                $query->expr()->like('deal.services',':search'),
                $query->expr()->like('deal.terms',':search')
            ))
            ->setParameter('search', '%' . $request->get('search') . '%');
        }

        $deals = $query
            ->getQuery()
            ->getResult()
        ;

        $dealsTypes = $em->getRepository('StoCoreBundle:Dictionary\Deal')
            ->createQueryBuilder('dictionary')
            ->orderBy('dictionary.position', 'ASC')
            ->getQuery()
            ->getResult()
        ;

        return [
            'deals' => $deals,
            'dictionaries' => $dealsTypes
        ];
    }

    /**
     * @Route("/deals/show", name="deals_show")
     * @Method("POST")
     * @Template()
     */
    public function dealsAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository('StoCoreBundle:Deal')
            ->createQueryBuilder('deal')
            ->where('deal.endDate > :endDate')
            ->setParameter('endDate', new \DateTime('now'))
        ;
        if ($request->get('deal_type')) {
            $deal_type = $request->get('deal_type');
            $query->andWhere('deal.typeId = :type')
                ->setParameter('type', $request->get('deal_type'));
        } else
            $deal_type = 0;
        $query->getQuery();

        $deals = $this->get('knp_paginator')->paginate(
            $query,
            $this->get('request')->query->get('page',1),
            10
        );

        return [
            'deals' => $deals,
            'deal_type' => $deal_type,
        ];
    }

    /**
     * @Route("/deal/{id}", name="content_deal_show")
     * @Method("GET")
     * @Template()
     * @ParamConverter("deal", class="StoCoreBundle:Deal")
     */
    public function showAction(Deal $deal)
    {
        return [
            'deal' => $deal
        ];
    }

    /**
     * @Route("/deal/{id}/write-feedback", name="write_deal_feedback")
     * @Method("GET")
     * @Template()
     * @ParamConverter("deal", class="StoCoreBundle:Deal")
     */
    public function writeFeedbackAction(Deal $deal)
    {
        return [
            'deal' => $deal,
        ];
    }
}
