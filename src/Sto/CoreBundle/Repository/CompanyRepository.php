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
     * @param array $params
     * @return array
     */
    public function getCompaniesWithFilter($params = array()) {
        $qb = $this->createQueryBuilder('company')
            ->select('company, csp, fb, d')
            ->join('company.specialization', 'csp')
            ->join('company.services', 'cs')
            ->join('company.deals', 'd')
            ->leftJoin('company.feedbacks', 'fb')
            ->where('company.visible = true');

        if (isset($params['city']) && $params['city']) {
            $qb->andWhere('company.city = :city')
                ->setParameter('city', $params['city']
                ->getId());
        }

        if (isset($params['companyType']) && $params['companyType']) {
            $qb->andWhere('csp.id = :sp')
                ->setParameter('sp', $params['companyType']);
        }

        if (isset($params['subCompanyType']) && $params['subCompanyType']) {
            $qb->andWhere('cs.id = :s')
                ->setParameter('s', $params['subCompanyType']);
        }

        if (isset($params['deals']) && $params['deals']) {
            $qb->andWhere('d.id IS NOT NULL');
        }

        if (isset($params['rating']) && $params['rating']) {
            $qb->andWhere('company.rating BETWEEN :rating-0.01 AND 10.01')
                ->setParameter('rating', $params['rating']);
        }

        if (isset($params['search']) && $params['search']) {
            $words = explode(" ", $params['search']);
            foreach ($words as $word) {
                $qb->andWhere($qb->expr()->orx(
                        $qb->expr()->like('company.name',':search'),
                        $qb->expr()->like('company.fullName',':search'),
                        $qb->expr()->like('company.description',':search'),
                        $qb->expr()->like('company.slogan',':search'),
                        $qb->expr()->like('cs.name',':search')
                    ))
                    ->setParameter('search', "%{$word}%");
            }
        }

        $tabNum = 0;
        foreach ($params['filter'] as $key => $value) {
            if ($params['filter'][$key]) {
                $qb->join('company.additionalServices', "cas{$tabNum}")
                    ->andWhere($qb->expr()->like("cas{$tabNum}.shortName", "'{$key}%'"));
                $tabNum++;
            }
        }

        if ($params['sort'] == 'price') {
            $qb->orderBy('company.hourPrice','ASC');
        } else {
            $qb->orderBy('company.rating','DESC');
        }

        $result = $qb->getQuery()->getArrayResult();

        /**
         * Неведомая мне хрень, начало
         */
        if ($params['timing']['weekends']) {
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

        if ($params['timing']['around_the_clock']) {
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

        if ($params['timing']['late']) {
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
