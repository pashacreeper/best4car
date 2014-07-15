<?php

namespace Sto\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;

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
