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

    public function getCompaniesWithFilter($companyType = null, $subCompanyType = null, $auto = null, $rating = null, $filter = null, $deals = null, $timing = null)
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
        if ($subCompanyType) {
            $qb->andwhere('cs.id = :s')
                ->setParameter('s', $subCompanyType)
            ;
        }
        if ($rating) {
            $qb->andwhere('c.rating BETWEEN :rating AND 10')
                ->setParameter('rating', $rating)
            ;
        }
        $tabNum = 0;
        foreach ($filter as $key => $value) {
            if ($filter[$key]) {
                $qb->join('c.additionalServices', "cas{$tabNum}")
                    ->andWhere($qb->expr()->like("cas{$tabNum}.shortName","'$key%'"))
                ;
                $tabNum++;
            }
        }
        if ($deals) {
            $qb->join('c.deals', 'd')
                ->andWhere(
                    $qb->expr()->andX(
                        $qb->expr()->gte($qb->expr()->literal(date('Y-m-d')),'d.startDate'),
                        $qb->expr()->lte($qb->expr()->literal(date('Y-m-d')),'d.endDate')
                    )
                )
            ;
        }
        $query = $qb->getQuery();
        $result = $query->getArrayResult();

        if ($timing['weekends']) {
            $qbd = $this->getEntityManager()
                ->createQuery('SELECT u.id FROM StoCoreBundle:Dictionary\WeekDay u WHERE u.position in (5,6)')
                ->getArrayResult()
            ;
            $weekends = []; foreach ($qbd as $row) { $weekends[] = $row['id']; };
            $trueResult = [];
            foreach ($result as $row) {
                $leave = false;
                foreach ($row['workingTime'] as $workChunk) {
                    $workWeekDays = $workChunk['days']['array'];
                    $intersect = array_intersect($weekends,$workWeekDays);
                    if (count($intersect)>0) { $leave = true; }
                }
                if ($leave) {
                    array_push($trueResult, $row);
                }
            }
            $result = $trueResult;
        }

        if ($timing['24hours']) {
            $hours24 = '00:00-23:59';
            $trueResult = [];
            foreach ($result as $row) {
                $leave = false;
                foreach ($row['workingTime'] as $workChunk) {
                    $from = $workChunk['from'];
                    $till = $workChunk['till'];
                    $workTime = $from->format("H:i")."-".$till->format("H:i");
                    if ($hours24==$workTime) {$leave=true;}
                }
                if ($leave) {
                    array_push($trueResult, $row);
                }
            }
            $result = $trueResult;
        }

        if ($timing['late']) {
            $latehours = '1800';
            $trueResult = [];
            foreach ($result as $row) {
                $leave = false;
                foreach ($row['workingTime'] as $workChunk) {
                    $till = $workChunk['till'];
                    $workTime = $till->format("Hi");
                    if ($latehours<$workTime) { $leave=true; }
                }
                if ($leave) {
                    array_push($trueResult, $row);
                }
            }
            $result = $trueResult;
        }

        return $result;
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
