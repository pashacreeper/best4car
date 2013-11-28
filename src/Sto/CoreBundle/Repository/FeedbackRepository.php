<?php

namespace Sto\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Sto\UserBundle\Entity\User;
use Doctrine\ORM\Query;

/**
 * CityRepository
 */
class FeedbackRepository extends EntityRepository
{
    public function findFeedbackAnswersCountForUser(User $user)
    {
        $qb = $this->createQueryBuilder('q');
        $qb
            ->where('q.user = :user')
            ->setParameter('user', $user)
        ;

        $feedbacks = $qb->getQuery()->execute();
        $feedbackIds = null;
        if (! empty($feedbacks)) {
            $feedbackIds = array_map(function ($item) {
                return $item->getId();
            }, $feedbacks);
        }

        $result = ['answers' => 0];
        if ($feedbackIds) {
            $qb = $this->getEntityManager()->createQueryBuilder();
            $qb
                ->select('COUNT(a) as answers')
                ->from('StoCoreBundle:FeedbackAnswer', 'a')
                ->where('a.feedbackId IN (:feedbackIds)')
                ->setParameter('feedbackIds', $feedbackIds)
            ;

            $result = $qb->getQuery()->getOneOrNullResult();
        }

        return $result;
    }

    /**
     * Get feedbacks query by type
     * @param  string $type
     * @param  int    $id
     * @param  string $filter
     * @param  string $sort
     * @return Query
     */
    public function getFeedbacksByTypeQuery($type, $id, $filter, $sort)
    {
        $em = $this->getEntityManager();
        if ('company' == $type) {
            $qb = $em->getRepository('StoCoreBundle:FeedbackCompany')
                ->createQueryBuilder('fc')
                ->where('fc.companyId = :company')
                ->setParameter('company', $id)
            ;
        } elseif ('deal' == $type) {
            $qb = $em->getRepository('StoCoreBundle:FeedbackDeal')
                ->createQueryBuilder('fc')
                ->where('fc.dealId = :deal')
                ->setParameter('deal', $id)
            ;
        } elseif ('profile' == $type) {
            $qb = $em->getRepository('StoCoreBundle:Feedback')
                ->createQueryBuilder('fc')
                ->where('fc.user = :user')
                ->setParameter('user', $id)
            ;
        } else {
            return new Response('Entity type is not valid (company, deal or profile)',404);
        }

        switch ($filter) {
            case("positive"):
                $qb->andWhere('fc.pluses - fc.minuses >= 3');
                break;
            case("negative"):
                $qb->andWhere('fc.minuses - fc.pluses >= 2');
                break;
            case("useful"):
                $qb->andWhere('fc.pluses > fc.minuses');
                break;
        }

        if ($sort == "rating") {
            $qb->orderBy("fc.feedbackRating","DESC");
        } else {
            $qb->orderBy("fc.date","DESC");
        }

        return $qb->getQuery();
    }
}
