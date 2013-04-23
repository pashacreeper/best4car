<?php

namespace Sto\ContentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Template,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sto\CoreBundle\Entity\Deal;

class DealController extends Controller
{
    /**
     * @Route("/deals", name="content_deals")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $deals = $em->getRepository('StoCoreBundle:Deal')
            ->createQueryBuilder('deal')
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
    public function dealsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository('StoCoreBundle:Deal')
            ->createQueryBuilder('deal')
            ->getQuery()
        ;

        $deals = $this->get('knp_paginator')->paginate(
            $query,
            $this->get('request')->query->get('page',1),
            10
        );

        //$page_params = $deals->getPaginationData();
        return [
            'deals' => $deals,
            //'params' => $page_params,
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
