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
use Sto\CoreBundle\Entity\FeedbackDeal;
use Sto\ContentBundle\Form\FeedbackDealType;
use Sto\ContentBundle\Form\DealType;

use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

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

        $deals = $repository->getActiveDealsByCompany($company->getId());

        $arhive_deals = $query
            ->getQuery()
            ->getResult()
        ;

        return [
            'archive_deal' => $arhive_deals,
            'company'      => $company,
            'deals'        => $deals
        ];
    }

    /**
     * Deletes a Deal entity.
     *
     * @Route("/company/{id}/deals/{dealId}/delete", name="company_deal_delete")
     * @Method({"GET","POST"})
     * @ParamConverter("company", class="StoCoreBundle:Company")
     * @Template()
     */
    public function deleteDealAction(Request $request,  $dealId, Company $company)
    {
        $em = $this->getDoctrine()->getManager();
        $deal = $em->getRepository('StoCoreBundle:Deal')->findOneById($dealId);

        if (!$deal) {
            throw $this->createNotFoundException('Unable to find Deal entity.');
        }

        $deleteForm = $this->createFormBuilder(['id' => $dealId])
            ->add('id', 'hidden')
            ->getForm();

        if ('POST' === $request->getMethod()) {
            $deleteForm->submit($request);
            if ($deleteForm->isValid()) {
                $user = $this->get('security.context')->getToken()->getUser();
                $resolution = false;
                foreach ($company->getCompanyManager() as $manager) {
                    if ($manager->getUser()->getUserName() == $user->getUserName()) {
                        $resolution = true;
                        break;
                    }
                }
                if (true === $this->get('security.context')->isGranted('ROLE_ADMIN') or $resolution) {
                    $em->remove($deal);
                    $em->flush();
                }

                return $this->redirect(
                    $this->generateUrl('content_company_show', ['id' => $company->getId()]) . '#deals'
                );
            }
        }

        return[
            'form' => $deleteForm->createView(),
            'deal' => $deal,
            'companyId' => $company->getId()
        ];
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

        $user = $this->get('security.context')->getToken()->getUser();
        $manyPlaces = $user->getCompanyManager()->count() > 1;

        $deal = new Deal($company);
        $form = $this->createForm(new DealType(), $deal, [
            'manyPlaces' => $manyPlaces,
            'user' => $user,
            'company' => $company,
        ]);

        return [
            'form'    => $form->createView(),
            'company' => $company,
            'isNew'   => true,
            'manyPlaces' => $manyPlaces,
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
        $user = $this->get('security.context')->getToken()->getUser();
        $manyPlaces = $user->getCompanyManager()->count() > 1;

        $deal = (new Deal)->setCompany($company);
        $form = $this->createForm(new DealType, $deal, [
            'manyPlaces' => $manyPlaces,
            'user' => $user,
            'company' => $company,
        ]);
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

                $this->get('sto.manager.feed')->createOnItem($deal);

                return $this->redirect(
                    $this->generateUrl('content_company_show', ['id' => $company->getId()]) . '#deals'
                );
            }
        }

        return [
            'deal'    => $deal,
            'form'    => $form->createView(),
            'company' => $company,
            'isNew'   => true,
            'manyPlaces' => $manyPlaces,
        ];
    }

    /**
     * Displays a form to edit an existing Deal entity.
     *
     * @Route("/company/{id}/deal/{dealId}/edit", name="company_deal_edit")
     * @ParamConverter("company", class="StoCoreBundle:Company")
     * @Template("StoContentBundle:Deal:newDeal.html.twig")
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

        $user = $this->get('security.context')->getToken()->getUser();
        $manyPlaces = $user->getCompanyManager()->count() > 1;

        $editForm = $this->createForm(new DealType(), $deal, [
            'manyPlaces' => $manyPlaces,
            'user' => $user,
            'company' => $company,
        ]);

        return [
            'manyPlaces' => $manyPlaces,
            'deal'        => $deal,
            'form'        => $editForm->createView(),
            'company'     => $company,
            'activ_deals' => $activ_deals,
            'isNew'       => false
        ];
    }

    /**
     * Edits an existing Deal entity.
     *
     * @Route("/company/{companyId}/deal/{id}/update", name="company_deal_update")
     * @Method({"POST"})
     * @Template("StoContentBundle:Deal:newDeal.html.twig")
     * @Secure(roles="IS_AUTHENTICATED_FULLY")
     */
    public function updateDealAction(Request $request, $id, $companyId)
    {
        $em = $this->getDoctrine()->getManager();
        $deal = $em->getRepository('StoCoreBundle:Deal')->findOneById($id);
        $company = $em->getRepository('StoCoreBundle:Company')->findOneById($companyId);

        if ($request->get("draft") !== null) {
            $deal->setDraft($deal->getDraft() xor true);
        }

        if (!$deal) {
            throw $this->createNotFoundException('Unable to find Deal entity.');
        }

        $user = $this->get('security.context')->getToken()->getUser();
        $manyPlaces = $user->getCompanyManager()->count() > 1;

        $editForm = $this->createForm(new DealType, $deal, [
            'manyPlaces' => $manyPlaces,
            'user' => $user,
            'company' => $company,
        ]);
        $editForm->bind($request);

        if ($editForm->isValid()) {
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
            'manyPlaces' => $manyPlaces,
            'deal'      => $deal,
            'form' => $editForm->createView(),
            'companyId' => $companyId,
            'company' => $company,
            'isNew'     => false
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
    public function dealsAction(Request $request)
    {
        $cityId = $this->get('sto_content.manager.city')->selectedCity()->getId();
        $em = $this->getDoctrine()->getManager();
        $search = $request->get('search');
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
     */
    public function showAction(Deal $deal)
    {
        $isManager = ($this->get('security.context')->isGranted("SHOW", $deal)) ? true : false;

        $refererRoute = $this->getRefererRoute();

        $refererCompany = $this->getRefererCompany();

        $companies = [];

        if ($additionalCompanies = $deal->getAdditionalCompanies()) {
            if (!$refererCompany || $refererCompany == $deal->getCompany()->getId()) {
                $companies[] = $deal->getCompany();
                foreach ($additionalCompanies as $company) {
                    $companies[] = $company;
                }
            } else {
                foreach ($additionalCompanies as $company) {
                    if ($company->getId() == $refererCompany) {
                        $companies[] = $company;
                        break;
                    }
                }
                $companies[] = $deal->getCompany();
                foreach ($additionalCompanies as $company) {
                    if ($company->getId() != $refererCompany) {
                        $companies[] = $company;
                        break;
                    }
                }
            }
        }

        return [
            'deal' => $deal,
            'companies' => $companies,
            'isManager' => $isManager,
            'refererRoute' => $refererRoute,
        ];
    }

    public function getRefererCompany()
    {
        $request = $this->getRequest();
        $refererCompany = null;
        if ($referer = $request->headers->get('referer')) {
            $urlParts = parse_url($referer);
            try {
                $path = $urlParts['path'];
                if (strpos($path, '/app_dev.php') === 0) {
                    $path = substr($path, strlen('/app_dev.php'));
                }
                if ($routeParams = $this->get('router')->match($path)) {
                    if ($routeParams['_route'] == 'content_company_show' && isset($routeParams['id'])) {
                        return (int) $routeParams['id'];
                    }
                }
            } catch (MethodNotAllowedException $e) {} catch(ResourceNotFoundException $e) {}
        }

        return $refererCompany;
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
        $form->submit($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->setUser($this->getUser())
                ->setDeal($deal)
                ->setPluses(0)
                ->setMinuses(0)
                ->setPublished(false)
                ->setIp($request->getClientIp())
            ;
            $this->get('sto.notifications.email')->sendCompanyDealFeedbackEmail($deal->getCompany(), $deal);
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
    public function editFeedbackAction(Deal $deal, $feedbackId)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('StoCoreBundle:FeedbackDeal');
        $feedback = $repository->findOneById($feedbackId);

        if (!$feedback) {
            throw $this->createNotFoundException('Unable to find FeedbackDeal entity.');
        }

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
