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
use Sto\CoreBundle\Entity\Company;
use Sto\CoreBundle\Entity\FeedbackDeal;
use Sto\ContentBundle\Form\FeedbackDealType;
use Sto\ContentBundle\Form\DealType;

class DealController extends Controller
{
    /**
     * Archive a Deal entity.
     * @Template()
     * @Route("/company/{id}/deals/arhive", name="company_deal_arhive")
     * @Method("GET")
     * @ParamConverter("company", class="StoCoreBundle:Company")
     */
    public function archiveDealAction(Company $company)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('StoCoreBundle:Deal');
        $query = $repository->createQueryBuilder('deal')
            ->where('deal.endDate < :endDate ')
            ->andwhere('deal.companyId = :company')
            ->setParameters(['endDate'=> new \DateTime('now'),'company'=> $company->getId()])
        ;

        $arhive_deals = $query
            ->getQuery()
            ->getResult()
        ;

        return [
            'archive_deal' => $arhive_deals,
            'companyId'    => $company->getId(),
        ];
    }

    /**
     * Deletes a Deal entity.
     *
     * @Route("/company/{id}/deals/{dealId}/delete", name="company_deal_delete")
     * @Method({"GET","POST"})
     * @ParamConverter("company", class="StoCoreBundle:Company")
     */
    public function deleteDealAction(Request $request,  $dealId, Company $company)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('StoCoreBundle:Deal')->findOneById($dealId);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Deal entity.');
        }

        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('content_company_show_tab', ['id' => $company->getId(),'tab'=>'deals']));
    }

    /**
     * Displays a form to create a new Deal entity.
     *
     * @Route("/company/{id}/deal/new", name="company_deal_new")
     * @Method({"GET"})
     * @Template()
     * @ParamConverter("company", class="StoCoreBundle:Company")
     */
    public function newDealAction(Company $company)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('StoCoreBundle:Deal');
        $activ_deals = $repository->createQueryBuilder('deal')
            ->where('deal.endDate > :endDate ')
            ->andwhere('deal.companyId = :company')
            ->andWhere('deal.draft = false')
            ->setParameters([
                'endDate'=> new \DateTime('now'),
                'company'=> $company->getId()
            ])
            ->getQuery()
            ->getResult()
        ;

        $deal = new Deal;
        $deal->setCompany($company);
        $form = $this->createForm(new DealType, $deal);

        return [
            'form'        => $form->createView(),
            'company'     => $company,
            'activ_deals' => $activ_deals
        ];
    }

    /**
     * Creates a new Deal entity.
     *
     * @Route("/company/{id}/deal/create", name="company_deal_create")
     * @Method({"POST"})
     * @Template("StoContentBundle:Deal:newDeal.html.twig")
     * @ParamConverter("company", class="StoCoreBundle:Company")
     */
    public function createDealAction(Request $request, Company $company)
    {
        $deal = (new Deal)->setCompany($company);
        $form = $this->createForm(new DealType, $deal);
        $form->bind($request);

        if ($form->isValid()) {
            if ($request->get("draft")) {
                $deal->setDraft(true);
            }

            $user = $this->get('security.context')->getToken()->getUser();
            $resolution = false;
            foreach ($company->getManagers() as $key => $value) {
                if ( $value->getUserName() == $user->getUserName()) {
                    $resolution = true;
                    break;
                }
            }
            if (true === $this->get('security.context')->isGranted('ROLE_ADMIN') or $resolution) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($deal);
                $em->flush();
            }

            return $this->redirect($this->generateUrl('content_company_show_tab', ['id' => $company->getId(),'tab'=>'deals']));
        }

        return [
            'deal' => $deal,
            'form' => $form->createView(),
            'company' => $company
        ];
    }

   /**
     * Displays a form to edit an existing Deal entity.
     *
     * @Route("/company/{id}/deal/{dealId}/edit", name="company_deal_edit")
     * @ParamConverter("company", class="StoCoreBundle:Company")
     * @Template()
     */
   public function editDealAction(Company $company ,$dealId)
   {
    $em = $this->getDoctrine()->getManager();
    $repository = $em->getRepository('StoCoreBundle:Deal');
    $deal = $repository->findOneById($dealId);

    if (!$deal) {
        throw $this->createNotFoundException('Unable to find Deal entity.');
    }

    $activ_deals = $repository->createQueryBuilder('deal')
        ->where('deal.endDate > :endDate ')
        ->andwhere('deal.companyId = :company')
        ->andWhere('deal.draft = false')
        ->setParameters([
            'endDate'=> new \DateTime('now'),
            'company'=> $company->getId()
        ])
        ->getQuery()
        ->getResult()
    ;

    $editForm = $this->createForm(new DealType, $deal);

    return [
        'deal'        => $deal,
        'edit_form'   => $editForm->createView(),
        'companyId'   => $company->getId(),
        'activ_deals' => $activ_deals
    ];
}

    /**
     * Edits an existing Deal entity.
     *
     * @Route("/company/{companyId}/deal/{id}/update", name="company_deal_update")
     * @Method({"POST"})
     * @Template("StoContentBundle:Deal:editDeal.html.twig")
     */
    public function updateDealAction(Request $request, $id, $companyId )
    {
        $em = $this->getDoctrine()->getManager();
        $deal = $em->getRepository('StoCoreBundle:Deal')->findOneById($id);

        if ($request->get("draft") !== null) {
            $deal->setDraft($deal->getDraft() xor true);
        }

        if (!$deal) {
            throw $this->createNotFoundException('Unable to find Deal entity.');
        }

        $editForm = $this->createForm(new DealType, $deal);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $company = $em->getRepository('StoCoreBundle:Company')->findOneById($companyId);
            $user = $this->get('security.context')->getToken()->getUser();
            $resolution = false;
            foreach ($company->getManagers() as $key => $value) {
                if ( $value->getUserName() == $user->getUserName()) {
                    $resolution = true;
                    break;
                }
            }
            if (true === $this->get('security.context')->isGranted('ROLE_ADMIN') or $resolution) {
                $em->persist($deal);
                $em->flush();
            }

            return $this->redirect($this->generateUrl('content_company_show_tab',['id'=>$companyId, 'tab'=>'deals']));
        }

        return [
            'deal'      => $deal,
            'edit_form' => $editForm->createView(),
            'companyId' => $companyId
        ];
    }

    /**
     * @Route("/deals", name="content_deals")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $city = $this->get('sto_content.manager.city')->selectedCity();
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('StoCoreBundle:Deal');
        $query = $repository->createQueryBuilder('deal')
            ->join('deal.company', 'dc')
            ->where('deal.endDate > :endDate')
            ->andWhere('dc.cityId = :city')
            ->setParameters([
                'endDate' => new \DateTime('now'),
                'city' => $city->getId()
            ])
        ;

        if ($request->get('search')) {
            $query->andWhere($query->expr()->orx(
                    $query->expr()->like('deal.name',':search'),
                    $query->expr()->like('deal.description',':search'),
                    $query->expr()->like('deal.services',':search'),
                    $query->expr()->like('deal.terms',':search')
                ))
                ->setParameter('search', '%' . $request->get('search') . '%')
            ;
        }

        $deals = $query
            ->getQuery()
            ->getResult()
        ;

        $dealsTypes = $em->getRepository('StoCoreBundle:Dictionary\Deal')
            ->createQueryBuilder('dictionary')
            ->select('dictionary, deals')
            ->join('dictionary.deals', 'deals')
            ->join('deals.company', 'dc')
            ->where('deals.endDate > :endDate')
            ->andWhere('dc.cityId = :city')
            ->setParameters([
                'endDate' => new \DateTime('now'),
                'city' => $city->getId()
            ])
            ->orderBy('dictionary.position', 'ASC')
            ->getQuery()
            ->getResult()
        ;

        $countFeededDeals = $repository->createQueryBuilder('deal')
            ->join('deal.feedbacks', 'f')
            ->join('deal.company', 'dc')
            ->where('deal.endDate > :endDate AND f.content is not null')
            ->andWhere('dc.cityId = :city')
            ->setParameters([
                'endDate' => new \DateTime('now'),
                'city' => $city->getId()
            ])
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
     * @Method({"POST"})
     * @Template()
     */
    public function dealsAction(Request $request)
    {
        $city = $this->get('sto_content.manager.city')->selectedCity();
        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository('StoCoreBundle:Deal')
            ->createQueryBuilder('deal')
            ->join('deal.company', 'dc')
            ->where('deal.endDate > :endDate')
            ->andWhere('dc.cityId = :city')
            ->setParameters([
                'endDate' => new \DateTime('now'),
                'city' => $city->getId()
            ])
        ;

        $deal_type = $request->get('deal_type') ? $request->get('deal_type') : 0;
        if ($deal_type > 0) {
            $query->andWhere('deal.typeId = :type')
                ->setParameter('type', $request->get('deal_type'))
            ;
        } elseif ($deal_type == -2) {
            $query->join('deal.feedbacks', 'f')
                ->andWhere('f.content is not null')
            ;
        } else {
            $deal_type = 0;
        }

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
     * @Route("/deal-feedbacks/{id}", name="deal_feedbacks_show")
     * @Method("POST")
     * @Template()
     */
    public function feedbacksAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository('StoCoreBundle:FeedbackDeal')
            ->createQueryBuilder('fd')
            ->where('fd.dealId = :deal')
            ->setParameter('deal', $id)
            ->getQuery()
        ;

        $feedbacks = $this->get('knp_paginator')->paginate(
            $query,
            $this->get('request')->query->get('page',1),
            3
        );

        $deal = $em->getRepository('StoCoreBundle:Deal')->findOneById($id);

        $manager = $em->getRepository('StoCoreBundle:CompanyManager')
            ->createQueryBuilder('cm')
            ->where('cm.userId = :user_id AND cm.companyId = :company')
            ->setParameter('user_id', $this->getUser()->getId())
            ->setParameter('company', $deal->getCompanyId())
            ->getQuery()
            ->getResult()
            ;
        $isManager = (isset($manager) && count($manager) > 0) ? true : false;

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
     * @Route("/deal/{id}/feedback/add", name="content_deal_feedbacks_add")
     * @Method("GET")
     * @ParamConverter("deal", class="StoCoreBundle:Deal")
     * @Template()
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

            return $this->redirect($this->generateUrl('content_deal_show', ['id' => $deal->getId()]));
        }

        return [
            'form' => $form->createView(),
            'deal' => $deal
        ];
    }

    /**
     * @Route("/deal/{id}/feedback/{feedbackId}/edit", name="content_deal_feedbacks_edit")
     * @ParamConverter("deal", class="StoCoreBundle:Deal")
     * @Template()
     */
    public function editFeedbackAction(Deal $deal,$feedbackId)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('StoCoreBundle:FeedbackDeal');
        $feedback = $repository->findOneById($feedbackId);

        if (!$feedback) {
            throw $this->createNotFoundException('Unable to find FeedbackDeal entity.');
        }
        ;
        $editForm = $this->createForm(new FeedbackDealType, $feedback->setDeal($deal));

        return [
            'editForm' => $editForm->createView(),
            'deal' => $deal,
            'feedback' => $feedback
        ];
    }

    /**
     * @Route("/deal/{id}/feedback/{feedbackId}/update", name="content_deal_feedbacks_update")
     * @Method("POST")
     * @ParamConverter("deal", class="StoCoreBundle:Deal")
     * @Template("StoContentBundle:Deal:editFeedback.html.twig")
     */
    public function updateFeedbackAction(Request $request, Deal $deal,$feedbackId)
    {
        $em = $this->getDoctrine()->getManager();
        $feedback = $em->getRepository('StoCoreBundle:FeedbackDeal')->findOneById($feedbackId);

        if (!$feedback) {
            throw $this->createNotFoundException('Unable to find FeedbackDeal entity.');
        }

        $editForm = $this->createForm(new FeedbackDealType, $feedback);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($feedback);
            $em->flush();

            return $this->redirect($this->generateUrl('content_deal_show', ['id' => $deal->getId()]));
        }

        return [
            'feedback' => $feedback,
            'editForm' => $editForm->createView(),
            'deal' => $deal
        ];
    }

    /**
     * @Route("/deal/{id}/feedback-answer/add", name="content_deal_feedbacks_answer_add")
     * @Method("POST")
     * @Template()
     * @Secure(roles="IS_AUTHENTICATED_FULLY")
     */
    public function addFeedbackAnswerAction(Request $request, $id)
    {
        $feedback_id = $request->get('feedback_id');
        $em = $this->getDoctrine()->getManager();
        $feedback = $em->getRepository('StoCoreBundle:Feedback')->findOneById($feedback_id);
        if (!$feedback)
            return new Responce(500, 'Feedback Not found.');
        $answer = new FeedbackAnswer();
        $answer->setAnswer($request->get('answer'));
        $answer->setOwner($this->getUser());
        $answer->setFeedback($feedback);
        $answer->setDate(new \DateTime('now'));
        $em->persist($answer);
        $em->flush();

        return $this->redirect($this->generateUrl('content_deal_show', ['id' => $id]));
    }
}
