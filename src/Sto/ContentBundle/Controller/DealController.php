<?php
namespace Sto\ContentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sto\ContentBundle\Controller\ChoiceCityController as MainController;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Sto\CoreBundle\Entity\Deal;
use Sto\CoreBundle\Entity\Company;
use Sto\CoreBundle\Entity\CompanyManager;
use Sto\CoreBundle\Entity\FeedbackDeal;
use Sto\ContentBundle\Form\FeedbackDealType;
use Sto\ContentBundle\Form\DealType;

class DealController extends MainController
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
            'company'      => $company,
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

        $user = $this->get('security.context')->getToken()->getUser();
        $resolution = false;
        foreach ($company->getCompanyManager() as $manager) {
            if ($manager->getUser()->getUserName() == $user->getUserName()) {
                $resolution = true;
                break;
            }
        }
        if (true === $this->get('security.context')->isGranted('ROLE_ADMIN') or $resolution) {
            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect(
            $this->generateUrl('content_company_show', ['id' => $company->getId()]) . '#deals'
        );
    }

    /**
     * Displays a form to create a new Deal entity.
     *
     * @Route("/company/{id}/deal/new", name="company_deal_new")
     * @Method({"GET"})
     * @Template()
     * @ParamConverter("company", class="StoCoreBundle:Company")
     * @Secure(roles="IS_AUTHENTICATED_FULLY")
     */
    public function newDealAction(Company $company)
    {
        $em = $this->getDoctrine()->getManager();

        $deal = new Deal($company);
        $form = $this->createForm(new DealType(), $deal);

        return [
            'form'        => $form->createView(),
            'company'     => $company,
        ];
    }

    /**
     * Creates a new Deal entity.
     *
     * @Route("/company/{id}/deal/create", name="company_deal_create")
     * @Method({"POST"})
     * @Template("StoContentBundle:Deal:newDeal.html.twig")
     * @ParamConverter("company", class="StoCoreBundle:Company")
     * @Secure(roles="IS_AUTHENTICATED_FULLY")
     */
    public function createDealAction(Request $request, Company $company)
    {
        $deal = (new Deal)->setCompany($company);
        $form = $this->createForm(new DealType, $deal);
        $form->submit($request);

        if ($form->isValid()) {
            if ($request->get("draft")) {
                $deal->setDraft(true);
            }

            $user = $this->get('security.context')->getToken()->getUser();
            $resolution = false;
            foreach ($company->getCompanyManager() as $manager) {
                if ($manager->getUser()->getUserName() == $user->getUserName()) {
                    $resolution = true;
                    break;
                }
            }
            if (true === $this->get('security.context')->isGranted('ROLE_ADMIN') or $resolution) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($deal);
                $em->flush();

                return $this->redirect(
                    $this->generateUrl('content_company_show', ['id' => $company->getId()]) . '#deals'
                );
            }
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
     * @Secure(roles="IS_AUTHENTICATED_FULLY")
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
            'company'   => $company,
            'activ_deals' => $activ_deals
        ];
    }

    /**
     * Edits an existing Deal entity.
     *
     * @Route("/company/{companyId}/deal/{id}/update", name="company_deal_update")
     * @Method({"POST"})
     * @Template("StoContentBundle:Deal:editDeal.html.twig")
     * @Secure(roles="IS_AUTHENTICATED_FULLY")
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
            foreach ($company->getCompanyManager() as $key => $value) {
                if ( $value->getUser()->getUserName() == $user->getUserName()) {
                    $resolution = true;
                    break;
                }
            }
            if (true === $this->get('security.context')->isGranted('ROLE_ADMIN') or $resolution) {
                $em->persist($deal);
                $em->flush();

                return $this->redirect($this->generateUrl('content_deal_show', ['id' => $deal->getId()]));
            }

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
        $cityId = $this->get('sto_content.manager.city')->selectedCity()->getId();
        $search = $request->get('search');
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('StoCoreBundle:Deal');

        $deals = $repository->getDeals($cityId, $search);
        $dealsTypes = $repository->getDealTypes($cityId, $search);
        $countDealsWithFeedback =$repository->getDealsWithFeedbacksCount($cityId, $search);
        $countPopularDeals = $repository->getPopularDealsCount($cityId, $search);
        $vipDeals = $repository->getVipDeals($cityId);

        return [
            'deals' => $deals,
            'dictionaries' => $dealsTypes,
            'countDealsWithFeedback' => $countDealsWithFeedback,
            'countPopularDeals' => $countPopularDeals,
            'vipDeals' => $vipDeals,
            'search' => $search,
        ];
    }

    /**
     * @Route("/deals/show", name="deals_show", options={"expose"=true})
     * @Method({"POST"})
     * @Template()
     */
    public function dealsAction(Request $request, $search = null)
    {
        $cityId = $this->get('sto_content.manager.city')->selectedCity()->getId();
        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository('StoCoreBundle:Deal')->getDealsQuery($cityId, $search);

        $dealType = $request->get('deal_type', 0);
        if ($dealType > 0) {
            $query->andWhere('deal.typeId = :type')
                ->setParameter('type', $dealType)
            ;
        } elseif ($dealType == -2) {
            $query->join('deal.feedbacks', 'f')
                ->andWhere('f.content is not null')
            ;
        } elseif ($dealType == -1) {
            $query->join('deal.feedbacks', 'f')
                ->having('COUNT(f.id) > 5')
                ->groupBy('deal.id')
            ;
        }

        $page = $this->get('request')->query->get('page', 1);
        $deals = $this->get('knp_paginator')->paginate($query->getQuery(), $page, 6);

        return [
            'deals' => $deals,
            'dealType' => $dealType,
        ];
    }

    /**
     * @Route("/deal/{id}", name="content_deal_show")
     * @Method("GET")
     * @Template()
     * @ParamConverter("deal", class="StoCoreBundle:Deal")
     */
    public function showAction(Request $request, Deal $deal)
    {
        if ($this->getUser()) {
            $em = $this->getDoctrine()->getManager();
            $manager = $em->getRepository('StoCoreBundle:CompanyManager')
                ->createQueryBuilder('cm')
                ->where('cm.userId = :user_id AND cm.companyId = :company')
                ->setParameter('user_id', $this->getUser()->getId())
                ->setParameter('company', $deal->getCompany()->getId())
                ->getQuery()
                ->getResult()
            ;
        }

        $refererRoute = $this->getRefererRoute();

        $isManager = (isset($manager) && count($manager)>0) ? true : false;

        return [
            'deal' => $deal,
            'isManager' => $isManager,
            'refererRoute' => $refererRoute,
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
        $form = $this->createForm(new FeedbackDealType, new FeedbackDeal($this->getUser(), $deal));

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
        $entity = new FeedbackDeal();
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

            return $this->redirect($this->generateUrl('content_deal_show', ['id' => $deal->getId()]).'#feedbacks');
        }

        return [
            'form' => $form->createView(),
            'deal' => $deal
        ];
    }

    /**
     * @Route("/deal/{id}/feedback/{feedbackId}/edit", name="content_deal_feedbacks_edit", options={"expose"=true})
     * @ParamConverter("deal", class="StoCoreBundle:Deal")
     * @Template("StoContentBundle:Deal:editFeedback.html.twig")
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
            'form' => $editForm->createView(),
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

            return $this->redirect($this->generateUrl('content_deal_show', ['id' => $deal->getId()]) . '#feedbacks');
        }

        return [
            'feedback' => $feedback,
            'form' => $editForm->createView(),
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
            return new Response('Feedback Not found.', 500);
        $answer = new FeedbackAnswer();
        $answer->setAnswer($request->get('answer'));
        $answer->setOwner($this->getUser());
        $answer->setFeedback($feedback);
        $answer->setDate(new \DateTime('now'));
        $em->persist($answer);
        $em->flush();

        return $this->redirect($this->generateUrl('content_deal_show', ['id' => $id]));
    }

    /**
     * Load list of search variants
     * @Template()
     */
    public function searchVariantsAction()
    {
        $list = [
            'сезонный шиномонтаж',
            'бесплатный осмотр подвески',
            'скидка на ТО volvo',
        ];

        return [
            'list' => $list
        ];
    }
}
