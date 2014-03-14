<?php

namespace Sto\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * CompanySpecializationRepository
 */
class CompanySpecializationRepository extends EntityRepository
{
    public function getByCompany($company)
    {
        return $this->createQueryBuilder('s')
            ->select('s, st, sst')
            ->join('s.type', 'st')
            ->join('s.subType', 'sst')
            ->where('s.company = :company')
            ->setParameter('company', $company)
            ->getQuery()
            ->getResult()
        ;
    }
}
