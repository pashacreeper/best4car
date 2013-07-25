<?php
namespace Sto\ContentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class FeedbackController extends Controller
{
    /**
     * @Route("/company-feedbacks/{id}", name="company_feedbacks_show")
     * @Method("POST")
     * @Template("StoContentBundle:Feedback:feedback.html.twig")
     */
    public function companyFeedbacksAction($id)
    {
        $feedbacks = $this->getFeedbacks($id, 'company');
        $isManager = $this->isManager($id);

        $date = new \DateTime();
        $date->modify('-15 hours');

        return [
            'feedbacks' => $feedbacks,
            'companyId' => $id,
            'isManager' => $isManager,
            'date' => $date
        ];
    }

    /**
     * @Route("/deal-feedbacks/{id}", name="deal_feedbacks_show")
     * @Method("POST")
     * @Template("StoContentBundle:Feedback:feedback.html.twig")
     */
    public function dealFeedbacksAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $deal = $em->getRepository('StoCoreBundle:Deal')->findOneById($id);

        $feedbacks = $this->getFeedbacks($id, 'deal');
        $isManager = $this->isManager($deal->getCompanyId());

        $date = new \DateTime();
        $date->modify('-15 hours');

        return [
            'feedbacks' => $feedbacks,
            'dealId' => $id,
            'isManager' => $isManager,
            'date' => $date
        ];
    }

    /**
     * Geting feedbacks for company, deal and may be other things
     * @param  int    $id   id of comapny or deal entity
     * @param  string $type type of entity
     * @return [type] [description]
     */
    private function getFeedbacks($id, $type)
    {
        $em = $this->getDoctrine()->getManager();
        if ('company' === $type) {
            $qb = $em->getRepository('StoCoreBundle:FeedbackCompany')
                ->createQueryBuilder('fc')
                ->where('fc.companyId = :company')
                ->setParameter('company', $id)
            ;

            if (!$this->get('security.context')->isGranted('ROLE_MODERATOR')) {
                $qb->andWhere('fc.hidden = :hidden')
                    ->setParameter('hidden', false);
            }
        } elseif ('deal' === $type) {
            $qb = $em->getRepository('StoCoreBundle:FeedbackDeal')
                ->createQueryBuilder('fd')
                ->where('fd.dealId = :deal')
                ->setParameter('deal', $id)
            ;
        }

        $query = $qb->getQuery();

        return $this->get('knp_paginator')->paginate(
            $query,
            $this->get('request')->query->get('page',1),
            10
        );
    }

    private function isManager($companyId)
    {
        $em = $this->getDoctrine()->getManager();
        if ($this->getUser()) {
            $manager = $em->getRepository('StoCoreBundle:CompanyManager')
                ->createQueryBuilder('cm')
                ->where('cm.userId = :user_id AND cm.companyId = :company')
                ->setParameter('user_id', $this->getUser()->getId())
                ->setParameter('company', $companyId)
                ->getQuery()
                ->getResult()
            ;
        }

        return (isset($manager) && count($manager) > 0) ? true : false;
    }
}
