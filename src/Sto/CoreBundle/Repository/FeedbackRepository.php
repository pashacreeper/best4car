<?php

namespace Sto\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Sto\UserBundle\Entity\User;

/**
 * CityRepository
 */
class FeedbackRepository extends EntityRepository
{
    public function findFeedbackAnsersCountForUser(User $user)
    {
        $qb = $this->createQueryBuilder('q');
        $qb
            ->where('q.user = :user')
            ->setParameter('user', $user)
        ;

        $feedbacks = $qb->getQuery()->execute();
        $feedbackIds = array_map(function($item){
            return $item->getId();
        }, $feedbacks);

        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb
            ->select('COUNT(a) as answers')
            ->from('StoCoreBundle:FeedbackAnswer', 'a')
            ->where('a.feedbackId IN (:feedbackIds)')
            ->setParameter('feedbackIds', $feedbackIds)
        ;

        return $qb->getQuery()->getOneOrNullResult();
    }
}
