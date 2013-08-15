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
use Sto\ContentBundle\Form\Type\CompaniesSortType;

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
        $entity =$em->getRepository('StoCoreBundle:Dictionary\CompanyType')
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
        if ($this->get('security.context')->isGranted('ROLE_FROZEN')) {
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }

        $em = $this->getDoctrine()->getManager();
        $city = $this->get('sto_content.manager.city')->selectedCity();
        $words = null;

        if ($request->isMethod('GET') && $request->get('search')) {
            $words = $request->get('search');
        }

        $companySortForm = $this->createForm(new CompaniesSortType());

        return [
            'city' => $city,
            'sortForm' => $companySortForm->createView(),
            'words' => $words
        ];
    }

    /**
     * @Route("/company/{id}", name="content_company_show", options={"expose"=true})
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

        $refererRoute = $this->getRefererRoute();

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
            ->setParameter('company', $company)
            ->getQuery()
            ->getResult()
        ;

        if (count($manager) || $this->get('security.context')->isGranted('ROLE_ADMIN')) {

            $form = $this->createForm(new CompanyType, $company, ['em'=> $em = $this->getDoctrine()->getManager()]);

            return [
                'cForm' => $form->createView(),
                'company' => $company,
            ];
        }

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

            return $this->redirect($this->generateUrl('content_company_show', ['id' => $company->getId()])."#feedbacks");
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
            return new Response('Feedback Not found.', 500);
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
