<?php

namespace Sto\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * CompanyAutoServiceRepository
 */
class CompanyAutoServiceRepository extends EntityRepository
{
    public function findBySpecializtions($specializations)
    {
        $ids = [];
        foreach ($specializations as $specialization) {
            $ids[] = $specialization->getId();
        }

        $result = $this->createQueryBuilder('s')
            ->where('s.specialization IN (:specializations)')
            ->andWhere('s.parent IS NULL')
            ->setParameter('specializations', $ids)
            ->getQuery()
            ->getResult()
        ;

        $entities = [];
        foreach ($result as $row) {
            $specialization = $row->getSpecialization()->getId();
            if (!isset($entities[$specialization])) {
                $entities[$specialization] = [];
            }
            $entities[$specialization][] = $row;
        }

        return $entities;
    }
}
