<?php

namespace Sto\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Sto\UserBundle\Entity\User;

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
            $feedbackIds = array_map(function($item){
                return $item->getId();
            }, $feedbacks);
        }

        $result = ['answers' => 0];
        if ($feedbackIds) {
            $qb = $this->getManager()->createQueryBuilder();
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
}
