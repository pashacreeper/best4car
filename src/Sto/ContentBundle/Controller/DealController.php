<?php

namespace Sto\ContentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Sto\CoreBundle\Entity\Deal;
use Sto\CoreBundle\Entity\FeedbackDeal;
use Sto\ContentBundle\Form\FeedbackDealType;

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
            ->select('dictionary, deals')
            ->join('dictionary.deals', 'deals')
            ->where('deals.endDate > :endDate')
            ->setParameter('endDate', new \DateTime('now'))
            ->orderBy('dictionary.position', 'ASC')
            ->getQuery()
            ->getResult()
        ;

        $countFeededDeals = $repository->createQueryBuilder('deal')
            ->join('deal.feedbacks', 'f')
            ->where('deal.endDate > :endDate AND f.content is not null')
            ->setParameter('endDate', new \DateTime('now'))
            ->getQuery()
            ->getResult()
       ;

        return [
            'deals' => $deals,
            'dictionaries' => $dealsTypes,
            'countFeededDeals' => count($countFeededDeals)
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
            if ($deal_type>0) {
                $query->andWhere('deal.typeId = :type')
                    ->setParameter('type', $request->get('deal_type'));
            } elseif ($deal_type == -2) {
                $query->join('deal.feedbacks', 'f')
                ->andWhere('f.content is not null')
                ;
            }
            /*elseif ($deal_type == -1) {
                $query->andWhere('deal.typeId = :type')
                    ->setParameter('type', $request->get('deal_type'));
            }*/
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
     * @Route("/deal/{id}/feedback/add", name="content_deal_feedbacks_add")
     * @Method("GET")
     * @ParamConverter("deal", class="StoCoreBundle:Deal")
     * @Template()
     * @Secure(roles="IS_AUTHENTICATED_FULLY")
     */
    public function addFeedbackAction(Deal $deal)
    {
        $form = $this->createForm(new FeedbackDealType, (new FeedbackDeal)->setDeal($deal));

        return [
            'form' => $form->createView(),
            'deal' => $deal
        ];
    }

    /**
     * @Route("/deal/{id}/feedback/create", name="content_deal_feedbacks_create")
     * @Method("POST")
     * @ParamConverter("deal", class="StoCoreBundle:Deal")
     * @Template("StoContentBundle:Deal:addFeedback.html.twig")
     * @Secure(roles="IS_AUTHENTICATED_FULLY")
     */
    public function createFeedbackAction(Request $request, Deal $deal)
    {
        $entity = new FeedbackDeal;
        $form = $this->createForm(new FeedbackDealType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->setUser($this->getUser())
                ->setVisitDate(new \DateTime('now'))
                ->setDeal($deal)
                ->setPluses(0)
                ->setMinuses(0)
                ->setPublished(false)
                ->setIp($request->getClientIp())
            ;
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('content_deal_show', array('id' => $deal->getId())));
        }

        return array(
            'form'   => $form->createView(),
            'deal' => $deal
        );
    }
}
