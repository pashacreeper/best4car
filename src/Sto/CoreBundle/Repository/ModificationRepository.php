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
}
