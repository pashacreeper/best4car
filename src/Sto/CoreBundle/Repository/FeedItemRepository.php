<?php

namespace Sto\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * FeedItemRepository
 */
class FeedItemRepository extends EntityRepository
{
    public function getByMarks(array $dealMarks, array $companyMarks)
    {
        $qb = $this->createQueryBuilder('f');

        $qb
            ->leftJoin('f.company', 'c')
            ->leftJoin('c.autos', 'ca')
            ->where('c.allAuto = true OR ca IN (:companyMarks)')
            ->setParameter('companyMarks', $companyMarks)
        ;

        $qb
            ->leftJoin('f.deal', 'd')
            ->leftJoin('d.auto', 'da')
            ->orWhere('d.allAuto = true OR da IN (:dealMarks)')
            ->setParameter('dealMarks', $dealMarks)
        ;

        $qb->orderBy('f.createdAt', 'DESC');

        return $qb->getQuery();
    }
}
