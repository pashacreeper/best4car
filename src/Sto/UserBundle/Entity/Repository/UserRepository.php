<?php
namespace Sto\UserBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    /**
     * Finding user by email or by name
     * @param  string $login
     * @return [type] [description]
     */
    public function findUserByNameOrByEmail($login)
    {
        $qb = $this->createQueryBuilder('user');
        $qb
            ->where('user.username = :login')
            ->orWhere('user.email = :login')
            ->setParameter('login', $login)
        ;

        return $qb->getQuery()->getOneOrNullResult();
    }

    public function findForFeedNotify($feedItem)
    {
        $marks = [];
        $allAuto = false;
        if ($company = $feedItem->getCompany()) {
            $allAuto = $company->getAllAuto();
            $marks = $company->getAutos();
        }
        if ($deal = $feedItem->getDeal()) {
            $allAuto = $deal->getAllAuto();
            $marks = $deal->getAuto();
        }

        $qb = $this->createQueryBuilder('u');
        $qb
            ->where('u.feedNotify = true')
            ->join('u.subscriptions', 's')
            ->andWhere('s.type = :type')
            ->setParameter('type', $feedItem->getType())
        ;

        if (!$allAuto) {
            $markIds = [];
            foreach ($marks as $mark) {
                $markIds[] = $mark->getId();
            }
            $qb
                ->andWhere('s.mark IN (:marks)')
                ->setParameter('marks', $markIds)
            ;
        }

        return $qb->getQuery()->getResult();
    }
}
