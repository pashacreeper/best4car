<?php

namespace Sto\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Sto\CoreBundle\Entity\Dictionary\Country;

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

    /**
     * @param  Country $city
     * @param  string  $companyType
     * @param  string  $subCompanyType
     * @param  string  $auto
     * @param  string  $rating
     * @param  array   $filter
     * @param  string  $deals
     * @param  array   $timing
     * @param  string  $sort
     * @return array
     */
    public function getCompaniesWithFilter(
        $city = null,
        $companyType = null,
        $subCompanyType = null,
        $auto = null,
        $rating = null,
        $filter = null,
        $deals = null,
        $timing = null,
        $sort = null
    ) {
        $qb = $this->createQueryBuilder('company')
            ->select('company, csp, fb, d')
            ->join('company.specialization', 'csp')
            ->join('company.services', 'cs')
            ->join('company.deals', 'd')
            ->leftJoin('company.feedbacks', 'fb')
            ->where('company.visible = true')
            ->andWhere('company.city = :city')
            ->setParameter('city', $city->getId())
        ;
        if ($companyType) {
            $qb->andWhere('csp.id = :sp')
                ->setParameter('sp', $companyType)
            ;
        }
        if ($subCompanyType) {
            $qb->andWhere('cs.id = :s')
                ->setParameter('s', $subCompanyType)
            ;
        }

        if ($deals) {
            $qb->andWhere('d.id IS NOT NULL');
        }

        if ($rating) {
            $qb->andWhere('company.rating BETWEEN :rating-0.01 AND 10.01')
                ->setParameter('rating', $rating)
            ;
        }
        $tabNum = 0;
        foreach ($filter as $key => $value) {
            if ($filter[$key]) {
                $qb->join('company.additionalServices', "cas{$tabNum}")
                    ->andWhere($qb->expr()->like("cas{$tabNum}.shortName","'$key%'"))
                ;
                $tabNum++;
            }
        }

        if ($sort == 'price') {
            $qb->orderBy('company.hourPrice','ASC');
        } else {
            $qb->orderBy('company.rating','DESC');
        }

        $result = $qb->getQuery()->getArrayResult();

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

        if ($timing['around_the_clock']) {
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
