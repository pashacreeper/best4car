<?php
namespace Sto\ContentBundle\Controller;

use JMS\SecurityExtraBundle\Annotation\Secure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sto\ContentBundle\Controller\ChoiceCityController as MainController;
use Sto\ContentBundle\Form\AdvancedSearchType;
use Sto\ContentBundle\Form\CompanyType;
use Sto\ContentBundle\Form\FeedbackCompanyType;
use Sto\ContentBundle\Form\Type\CompaniesSortType;
use Sto\CoreBundle\Entity\Company;
use Sto\CoreBundle\Entity\Feedback;
use Sto\CoreBundle\Entity\CompanyAutoService;
use Sto\CoreBundle\Entity\FeedbackCompany;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
        $entity = $em->getRepository('StoCoreBundle:CompanyType')
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
     * @Template("StoContentBundle:Company:tabs/information.html.twig")
     */
    public function informationAction(Company $company, $isManager)
    {
        $em = $this->getDoctrine()->getManager();

        $services = [];
        if (0 < $company->getSpecializations()->count()) {
            $services = $em->getRepository('StoCoreBundle:CompanyAutoService')->findBySpecializtions($company->getSpecializations());
        }

        $gallery = $em->getRepository('StoCoreBundle:Company')->getCompanyGallery($company);

        return [
            'company' => $company,
            'isManager' => $isManager,
            'services' => $services,
            'gallery' => $gallery
        ];
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

        $allTypes = $em->getRepository('StoCoreBundle:CompanyType')->findAll();
        $companiesCount = count($em->getRepository('StoCoreBundle:Company')->getCompanyIdsWithFilter(['search' => $words, 'city' => $city->getId(), 'time' => []]));
        $companiesCountPlural = $this->declensionOfNumerals($companiesCount, ['компания', 'компании', 'компаний']);

        return [
            'city' => $city,
            'sortForm' => $companySortForm->createView(),
            'words' => $words,
            'allTypes' => $allTypes,
            'companiesCount' => $companiesCount,
            'companiesCountPlural' => $companiesCountPlural,
        ];
    }

    protected function declensionOfNumerals($number, $titles) {
        $cases = [2, 0, 1, 1, 1, 2];
        return $titles[ ($number % 100 > 4 && $number % 100 < 20) ? 2 : $cases[($number % 10 < 5) ? $number % 10 : 5] ];
    }

    /**
     * @Route("/company/{id}", name="content_company_show", options={"expose"=true})
     * @Method("GET")
     * @Template()
     */
    public function showAction(Request $request, Company $company)
    {
        $em = $this->getDoctrine()->getManager();
        if (!$company->getVisible()) {
            throw $this->createNotFoundException('The company does not exist');
        }
        $companyId = $company->getId();

        $qb = $em->getRepository('StoCoreBundle:FeedbackCompany')
            ->createQueryBuilder('fc')
            ->where('fc.companyId = :company')
            ->setParameter('company', $companyId)
        ;

        if (!$this->get('security.context')->isGranted('ROLE_MODERATOR')) {
            $qb->andWhere('fc.hidden = :hidden')
               ->setParameter('hidden', 0);
        }

        $feedbacks = $qb->getQuery()->getResult();

        $isManager = false;
        if ($this->get('security.context')->isGranted("SHOW", $company)) {
            $isManager = true;
        }

        $archivedDealsCount = $em->getRepository('StoCoreBundle:Deal')->getArchivedDealsCountByCompany($companyId);
        $deals = $em->getRepository('StoCoreBundle:Deal')->getActiveDealsByCompany($companyId);

        $refererRoute = $this->getRefererRoute();

        $gallery = $em->getRepository('StoCoreBundle:Company')->getCompanyGallery($company);

        return [
            'company' => $company,
            'isManager' => $isManager,
            'feedbacks' => $feedbacks,
            'archivedDealsCount' => $archivedDealsCount,
            'deals' => $deals,
            'refererRoute' => $refererRoute,
            'gallery' => $gallery,
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
        $companies = $em->getRepository('StoCoreBundle:Company')->getCompaniesByCity(
            $this->get('sto_content.manager.city')->selectedCity()
        );

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
        $feedbackCompany = new FeedbackCompany();
        $form = $this->createForm(
            new FeedbackCompanyType(),
            $feedbackCompany->setCompany($company)
        );

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
        $entity = new FeedbackCompany();
        $form = $this->createForm(new FeedbackCompanyType(), $entity);
        $form->submit($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->setUser($this->getUser())
                ->setCompany($company)
                ->setPluses(0)
                ->setMinuses(0)
                ->setPublished(false)
                ->setIp($request->getClientIp())
            ;
            $this->get('sto.notifications.email')->sendCompanyFeedbackEmail($company);
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
