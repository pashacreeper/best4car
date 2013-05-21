<?php

namespace Sto\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * CompanyRepository
 */
class CompanyRepository extends EntityRepository
{
    public function getVisibleCompanies()
    {
        return $this->createQueryBuilder('company')
            ->where('company.visible = true')
            ->orderBy('company.rating', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function getCompaniesWithFilter($companyType = null, $subComppanyType = null, $auto = null, $rating = null)
    {
        $qb = $this->createQueryBuilder('c')
            ->select('c, csp')
            ->join('c.specialization', 'csp')
            ->join('c.services', 'cs')
            ->where('c.visible = true')
        ;
        if ($companyType) {
            $qb->andwhere('csp.id = :sp')
                ->setParameter('sp', $companyType)
            ;
        }
        if ($subComppanyType) {
            $qb->andwhere('cs.id = :s')
                ->setParameter('s', $subComppanyType)
            ;
        }
        if ($rating) {
            $qb->andwhere('c.rating BETWEEN :rating AND 10')
                ->setParameter('rating', $rating)
            ;
        }

        return $qb->getQuery()->getArrayResult();
    }

    public function getCompaniesByCity($city)
    {
        return $this->createQueryBuilder('company')
            ->where('company.visible = true')
            ->andWhere('company.cityId = :city')
            ->orderBy('company.rating', 'DESC')
            ->setParameter('city', $city->getId())
            ->getQuery()
            ->getResult()
        ;
    }
}
