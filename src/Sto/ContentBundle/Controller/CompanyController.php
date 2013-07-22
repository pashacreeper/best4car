<?php
namespace Sto\ContentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sto\ContentBundle\Controller\ChoiceCityController as MainController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Sto\CoreBundle\Entity\Company;
use Sto\CoreBundle\Entity\FeedbackCompany;
use Sto\CoreBundle\Entity\FeedbackAnswer;
use Sto\ContentBundle\Form\FeedbackCompanyType;
use Sto\ContentBundle\Form\CompanyType;
use Sto\ContentBundle\Form\AdvancedSearchType;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class CompanyController extends MainController
{
    /**
     * @Route("/specialization", name="company_specialization")
     * @Method({"POST","GET"})
     */
    public function specializationAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $specialization = $request->get('specialization');
        // var_dump($specialization);die();
        $entity =$em->getRepository('StoCoreBundle:Dictionary\Company')
            ->createQueryBuilder('services')
            ->where('services.parent in (:specializationId)')
            ->setParameter('specializationId',$specialization )
            ->getQuery()
            ->getArrayResult()
        ;
        $response = new Response(json_encode($entity), 200);
        $response->headers->set('Content-Type',' application-json; charset=utf8');

        return $response;
    }

    /**
     * @Route("/", name="_index", options={"expose"=true})
     * @Route("/catalog", name="content_companies")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $city = $this->get('sto_content.manager.city')->selectedCity();

        $repository = $em->getRepository('StoCoreBundle:Company');
        $query = $repository->createQueryBuilder('company')
            ->select('company, s')
            ->join('company.specialization', 's')
            ->where('company.visible = true')
            ->andWhere('company.cityId = :city')
            ->setParameter('city', $city->getId())
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

        foreach ($companies as $key => $value) {
            $companies[$key]['specialization_template'] = $this
                ->render('StoContentBundle:Company:specialization_list.html.twig', ['specializations' => $value['specialization']])->getContent()
            ;

            $companies[$key]['workingTime_template'] = $this
                ->render('StoContentBundle:Company:workingTime_list.html.twig', ['workingTime' => $value['workingTime']])->getContent()
            ;
        }
        if ($this->get('security.context')->isGranted('ROLE_FROZEN')) {
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }

        $formFactory = $this->container->get('fos_user.registration.form.factory');
        $userManager = $this->container->get('fos_user.user_manager');
        $dispatcher = $this->container->get('event_dispatcher');
        $user = $userManager->createUser();
        $user->setEnabled(true);
        $user->setRatingGroupId(1);
        $user->addRole('ROLE_USER');
        $form = $formFactory->createForm();
        $form->setData($user);

        return [
            'companies' => json_encode($companies),
            'city' => $city,
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/company/{id}", name="content_company_show", options={"expose"=true})
     * @Route("/company/{id}/{tab}", name="content_company_show_tab", options={"expose"=true})
     * @Method("GET")
     * @Template()
     */
    public function showAction(Request $request, $id, $tab = 'information')
    {
        $em = $this->getDoctrine()->getManager();
        $company = $em->getRepository('StoCoreBundle:Company')->findOneById($id);

        if ($this->getUser()) {
            $manager = $em->getRepository('StoCoreBundle:CompanyManager')
                ->createQueryBuilder('cm')
                ->where('cm.userId = :user_id AND cm.companyId = :company')
                ->setParameter('user_id', $this->getUser()->getId())
                ->setParameter('company', $id)
                ->getQuery()
                ->getResult()
            ;
        }
        $qb = $em->getRepository('StoCoreBundle:FeedbackCompany')
            ->createQueryBuilder('fc')
            ->where('fc.companyId = :company')
            ->setParameter('company', $id)
        ;

        if (!$this->get('security.context')->isGranted('ROLE_MODERATOR')) {
            $qb->andWhere('fc.hidden = :hidden')
                ->setParameter('hidden', 0);
        }

        $feedbacks = $qb->getQuery()->getResult();
        $isManager = (isset($manager) && count($manager) > 0) ? true : false;

        $archivedDeals = $em->getRepository('StoCoreBundle:Deal')
            ->createQueryBuilder('deal')
            ->select("COUNT(deal)")
            ->where('deal.endDate < :endDate')
            ->andwhere('deal.companyId = :company')
            ->setParameters(['endDate'=> new \DateTime('now'), 'company'=> $id])
            ->getQuery()
            ->getOneOrNullResult()
        ;

        $refererRoute = null;
        if ($referer = $request->headers->get('referer')) {
            $urlParts = parse_url($referer);
            try {
                if ($routeParams = $this->get('router')->match($urlParts['path'])) {
                    $refererRoute = $routeParams['_route'];
                }
            } catch (MethodNotAllowedException $e) {} catch(ResourceNotFoundException $e) {}
        }

        return [
            'company' => $company,
            'tab'     => $tab,
            'isManager' => $isManager,
            'feedbacks'  => $feedbacks,
            'archivedDeals' => $archivedDeals[1],
            'refererRoute' => $refererRoute,
        ];
    }

    /**
     * @Route("/company-edit/{id}", name="content_company_edit")
     * @Method("GET")
     * @ParamConverter("company", class="StoCoreBundle:Company")
     * @Template("StoContentBundle:Company:editCompany.html.twig")
     * @Secure(roles="IS_AUTHENTICATED_FULLY")
     */
    public function editCompanyAction(Company $company)
    {
        $em = $this->getDoctrine()->getManager();
        $manager = $em->getRepository('StoCoreBundle:CompanyManager')
            ->createQueryBuilder('cm')
            ->where('cm.userId = :user_id AND cm.companyId = :company')
            ->setParameter('user_id', $this->getUser()->getId())
            ->setParameter('company', $id)
            ->getQuery()
            ->getResult()
        ;

        if (count($manager) || $this->get('security.context')->isGranted('ROLE_ADMIN')) {

            $form = $this->createForm(new CompanyType, $company, ['em'=> $em = $this->getDoctrine()->getManager()]);

            return [
                'cForm' => $form->createView(),
                'company' => $company,
            ];
        } else

            return new Response('Page Not found.', 404);
    }

    /**
     * @Route("/company-update/{id}", name="content_company_update")
     * @Method("POST")
     * @ParamConverter("company", class="StoCoreBundle:Company")
     * @Template("StoContentBundle:Company:editCompany.html.twig")
     * @Secure(roles="IS_AUTHENTICATED_FULLY")
     */
    public function updateCompanyAction(Request $request, Company $company)
    {
        $form = $this->createForm(new CompanyType(), $company, ['em'=> $em = $this->getDoctrine()->getManager()]);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($company);
            $em->flush();

            return $this->redirect($this->generateUrl('content_company_show', ['id' => $company->getId()]));
        }

        return [
            'cForm'    => $form->createView(),
            'company' => $company,
        ];
    }

    /**
     * Ajax get companies
     *
     * @Route("/ajax/getall", name="company_ajax_get_all", options={"expose"=true})
     * @Template()
     */
    public function getAllAjaxAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $companies = $em->getRepository('StoCoreBundle:Company')->getCompaniesByCity($this->get('sto_content.manager.city')->selectedCity());

        if (!$companies) {
            return new Response('Companies Not found.', 500);
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

        $qb = $em->getRepository('StoCoreBundle:FeedbackCompany')
            ->createQueryBuilder('fc')
            ->where('fc.companyId = :company')
            ->setParameter('company', $id)
            ;

        if (!$this->get('security.context')->isGranted('ROLE_MODERATOR')) {
            $qb->andWhere('fc.hidden = :hidden')
                ->setParameter('hidden', false);
        }

        $query = $qb->getQuery();

        $feedbacks = $this->get('knp_paginator')->paginate(
            $query,
            $this->get('request')->query->get('page',1),
            3
        );

        if ($this->getUser()) {
            $manager = $em->getRepository('StoCoreBundle:CompanyManager')
                ->createQueryBuilder('cm')
                ->where('cm.userId = :user_id AND cm.companyId = :company')
                ->setParameter('user_id', $this->getUser()->getId())
                ->setParameter('company', $id)
                ->getQuery()
                ->getResult()
            ;
        }

        $isManager = (isset($manager) && count($manager) > 0) ? true : false;

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
     * @Route("/company/{id}/feedback/add", name="content_company_feedbacks_add")
     * @Method("GET")
     * @ParamConverter("company", class="StoCoreBundle:Company")
     * @Template()
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

            return $this->redirect($this->generateUrl('content_company_show', ['id' => $company->getId()]));
        }

        return [
            'form'    => $form->createView(),
            'company' => $company
        ];
    }

    /**
     * @Route("/company/feedback/{id}/edit", name="content_company_feedbacks_edit", options={"expose"=true})
     * @Method("GET")
     * @ParamConverter("feedback", class="StoCoreBundle:Feedback")
     * @Template("StoContentBundle:Company:addFeedback.html.twig")
     * @Secure(roles="IS_AUTHENTICATED_FULLY")
     */
    public function editFeedbackAction(FeedbackCompany $feedback)
    {
        $form = $this->createForm(new FeedbackCompanyType, $feedback);

        return [
            'form' => $form->createView(),
            'feedback' => $feedback,
            'company' => $feedback->getCompany(),
            'feededit' => true,
        ];
    }

    /**
     * @Route("/company/{id}/feedback/update", name="content_company_feedbacks_update")
     * @Method("POST")
     * @ParamConverter("feedback", class="StoCoreBundle:Feedback")
     * @Template("StoContentBundle:Company:addFeedback.html.twig")
     * @Secure(roles="IS_AUTHENTICATED_FULLY")
     */
    public function updateFeedbackAction(Request $request, FeedbackCompany $feedback)
    {
        $form = $this->createForm(new FeedbackCompanyType(), $feedback);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($feedback);
            $em->flush();

            return $this->redirect($this->generateUrl('content_company_show', ['id' => $feedback->getCompany()->getId()]));
        }

        return [
            'form'    => $form->createView(),
            'feedback' => $feedback,
            'company' => $feedback->getCompany(),
            'feededit' => true,
        ];
    }

    /**
     * @Route("/company/{id}/feedback-answer/add", name="content_company_feedbacks_answer_add")
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

        return $this->redirect($this->generateUrl('content_company_show', ['id' => $id]));
    }

    /**
     * @Template()
     */
    public function searchVariantsAction()
    {
        $list = [
            'ремонт коробки передач Mitsubishi Lancer',
            'замена лобового стекла BMW 39 кузов',
            'водительская мед.комиссия',
        ];

        return [
            'list' => $list
        ];
    }

    /**
     * @Template()
     */
    public function advancedSearchFormAction()
    {
        $form = $this->createForm(new AdvancedSearchType());

        return [
            'form' => $form->createView(),
        ];
    }

}
