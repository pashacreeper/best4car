<?php

namespace Sto\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Orx;

/**
 * ModificationRepository
 */
class ModificationRepository extends EntityRepository
{
    public function filterVisible($qb)
    {
        return $qb
            ->andWhere('e.visible = true')
        ;
    }

    public function findByModelAndYear($model, $year)
    {
        $queryBuilder = $this->createQueryBuilder('c');
    	return $queryBuilder->select('c.id, c.name')
            ->where('c.parent = :model')
            ->andWhere('c.startOfProduction <= :year')
            ->andWhere(
                $queryBuilder->expr()->orx(
                    $queryBuilder->expr()->gt('c.closingOfProduction', ':year'),
                    $queryBuilder->expr()->isNull('c.closingOfProduction')
                )
            )
            ->andWhere('c.visible = true')
            ->getQuery()
            ->setParameter('model', $model)
            ->setParameter('year', $year)
            ->getArrayResult()
        ;
    }
}
