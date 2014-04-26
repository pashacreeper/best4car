<?php

namespace Sto\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Orx;

/**
 * ModelRepository
 */
class ModelRepository extends EntityRepository
{
    public function filterVisible($qb)
    {
        return $qb
            ->andWhere('e.visible = true')
        ;
    }
}
