<?php
namespace Sto\ContentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination;
use Sto\UserBundle\Entity\User;
use Sto\ContentBundle\Form\Type\FeedbackSortType;
use Sto\ContentBundle\Form\Type\FeedbackFilterType;

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
     * @Route("/profile-feedback/{id}", name="profile_feedbacks_show")
     * @Method("POST")
     * @Template("StoContentBundle:Feedback:feedback.html.twig")
     */
    public function profileFeedbacksAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('StoUserBundle:User')->findOneById($id);

        $feedbacks = $this->getFeedbacks($id, 'profile');

        return [
            'feedbacks' => $feedbacks,
            'isManager' => false,
            'profile' => true
        ];
    }

    /**
     * @Template()
     */
    public function sortAndFilterFormAction()
    {
        $sortForm = $this->createForm(new FeedbackSortType());
        $filterForm = $this->createForm(new FeedbackFilterType());

        return [
            'sortForm' => $sortForm->createView(),
            'filterForm' => $filterForm->createView()
        ];
    }

    /**
     * Geting feedbacks for company, deal and may be other things
     * @param  int               $id   id of comapny or deal entity
     * @param  string            $type type of entity
     * @return SlidingPagination
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
        } elseif ('profile' === $type) {
            $qb = $em->getRepository('StoCoreBundle:Feedback')
                ->createQueryBuilder('fp')
                ->where('fp.user = :user')
                ->setParameter('user', $id)
            ;
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * Check if user is manager for company
     * @param  integer $companyId
     * @return boolean
     */
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
