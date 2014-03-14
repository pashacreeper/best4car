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
            ->select('s, ss, IDENTITY(s.specialization) as specialization_id, IDENTITY(s.parent) as parent_id')
            ->join('s.service', 'ss')
            ->where('s.specialization IN (:specializations)')
            ->setParameter('specializations', $ids)
            ->getQuery()
            ->getArrayResult()
        ;

        $entities = [];
        foreach ($result as $row) {
            if(!$row['parent_id']) {
                $specialization = $row['specialization_id'];
                if (!isset($entities[$specialization])) {
                    $entities[$specialization] = [];
                }
                $newRow = $row[0];
                $newRow['children'] = $this->getChildrenSpecs($row, $result);
                $entities[$specialization][] = $newRow;
            }
        }

        return $entities;
    }

    public function getChildrenSpecs($current, $entities)
    {
        $children = [];
        foreach ($entities as $child) {
            if($child['parent_id'] == $current[0]['id']) {
                $newRow = $child[0];
                $newRow['children'] = [];
                $children[] = $newRow;
            }
        }

        return $children;
    }
}
