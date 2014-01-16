<?php

namespace Sto\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * CityRepository
 */
class CompanyManagerRepository extends EntityRepository
{
    public function isUserManager($userId, $companyId)
    {
        return $this->createQueryBuilder('cm')
            ->where('cm.userId = :user_id AND cm.companyId = :company')
            ->setParameter('user_id', $userId)
            ->setParameter('company', $companyId)
            ->getQuery()
            ->getResult();
    }
}
