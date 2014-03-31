<?php

namespace Sto\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * DealRepository
 */
class DealRepository extends EntityRepository
{
    public function getDealTypes($cityId, $search = null)
    {
        $query = $this->createQueryBuilder('deal')
            ->select('dt.id, COUNT(DISTINCT deal.id) AS deals_count')
            ->leftJoin('deal.type', 'dt')
            ->leftJoin('deal.company', 'dc')
            ->leftJoin('deal.services', 'ds')
            ->leftJoin('deal.auto', 'mark')
            ->leftJoin('deal.autoServices', 'deal_auto_services')
            ->where("STR_TO_DATE(CONCAT_WS(' ', deal.endDate, deal.endTime), '%Y-%m-%d %H:%i:%s') > :endDate")
            ->andWhere('dc.cityId = :city')
            ->groupBy('dt.id')
            ->setParameters(
                [
                    'endDate' => new \DateTime('now'),
                    'city' => $cityId
                ]
            )
        ;

        if ($search) {
            $query->andWhere(
                $query->expr()->orx(
                    $query->expr()->like('deal.name', ':search'),
                    $query->expr()->like('deal.description', ':search'),
                    $query->expr()->like('deal.terms', ':search'),
                    $query->expr()->like('ds.name', ':search'),
                    $query->expr()->like('mark.name', ':search'),
                    $query->expr()->like('deal_auto_services.name', ':search')
                )
            )->setParameter('search', "%{$search}%");
        }

        $dealTypeCounts = [];
        foreach ($query->getQuery()->getResult() as $type) {
            $dealTypeCounts[$type['id']] = $type['deals_count'];
        }

        $dealsTypes = $this->_em
            ->getRepository('StoCoreBundle:DealType')
            ->findBy([], ['position' => 'ASC'])
        ;

        $response = [];
        foreach ($dealsTypes as $type) {
            $response[] = [
                'id' => $type->getId(),
                'name' => $type->getName(),
                'deals_count' => isset($dealTypeCounts[$type->getId()]) ? $dealTypeCounts[$type->getId()] : 0,
            ];
        }

        return $response;
    }

    public function getDeals($cityId, $search = null)
    {
        return $this->getDealsQuery($cityId, $search)
            ->getQuery()
            ->getResult()
        ;
    }

    public function getDealsQuery($cityId, $search = null)
    {
        $query = $this->createQueryBuilder('deal')
            ->join('deal.company', 'dc')
            ->leftJoin('dc.specializations', 'csp')
            ->leftJoin('csp.type', 'csp_type')
            ->leftJoin('csp.subType', 'csp_sub_type')
            ->leftJoin('dc.feedbacks', 'fb')
            ->leftJoin('dc.autoServices', 'auto_services')
            ->leftJoin('deal.autoServices', 'deal_auto_services')
            ->leftJoin('dc.additionalServices', 'casp')
            ->leftJoin('deal.auto', 'mark')
            ->leftJoin('deal.services', 'ds')
            ->where("STR_TO_DATE(CONCAT_WS(' ', deal.endDate, deal.endTime), '%Y-%m-%d %H:%i:%s') > :endDate")
            ->andWhere('dc.cityId = :city')
            ->orderBy('deal.id', 'DESC')
            ->setParameters(
                [
                    'endDate' => new \DateTime('now'),
                    'city' => $cityId
                ]
            )
        ;

        if ($search) {
            $query->andWhere(
                $query->expr()->orx(
                    $query->expr()->like('deal.name', ':search'),
                    $query->expr()->like('deal.description', ':search'),
                    $query->expr()->like('deal.terms', ':search'),
                    $query->expr()->like('ds.name', ':search'),
                    $query->expr()->like('mark.name', ':search'),
                    $query->expr()->like('deal_auto_services.name', ':search')
                )
            )->setParameter('search', "%{$search}%");
        }

        return $query;
    }

    public function getActiveDealsByCompany($companyId)
    {
        return $this->createQueryBuilder('deal')
            ->where("STR_TO_DATE(CONCAT_WS(' ', deal.endDate, deal.endTime), '%Y-%m-%d %H:%i:%s') >= :now")
            ->andWhere('deal.companyId = :company')
            ->andWhere('deal.draft = 0')
            ->setParameters(['now' => new \DateTime('now'), 'company' => $companyId])
            ->getQuery()
            ->getResult()
        ;
    }

    public function getActiveDaelsCountByCompany($companyId)
    {
        return $this->createQueryBuilder('deal')
            ->select('COUNT(deal)')
            ->where("STR_TO_DATE(CONCAT_WS(' ', deal.endDate, deal.endTime), '%Y-%m-%d %H:%i:%s')  >= :endDate")
            ->andWhere('deal.companyId = :company')
            ->andWhere('deal.draft = 0')
            ->setParameters(['endDate' => new \DateTime('now'), 'company' => $companyId])
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getArchivedDealsCountByCompany($companyId)
    {
        return $this->createQueryBuilder('deal')
            ->select("COUNT(deal)")
            ->where("STR_TO_DATE(CONCAT_WS(' ', deal.endDate, deal.endTime), '%Y-%m-%d %H:%i:%s')  < :endDate")
            ->andWhere('deal.companyId = :company')
            ->setParameters(['endDate' => new \DateTime('now'), 'company' => $companyId])
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    public function getVipDeals($cityId)
    {
        $now = new \DateTime('now');
        $query = $this->createQueryBuilder('deal')
            ->join('deal.company', 'dc')
            ->where("STR_TO_DATE(CONCAT_WS(' ', deal.endDate, deal.endTime), '%Y-%m-%d %H:%i:%s') >= :endDate")
            ->andWhere('dc.cityId = :city')
            ->andWhere('deal.is_vip = 1')
            ->setParameters(
                [
                    'endDate' => $now,
                    'city' => $cityId
                ]
            )
            ->getQuery()
        ;

        return $query->getResult();
    }

    public function getPopularDealsCount($cityId, $search = null)
    {
        $query = $this->createQueryBuilder('deal')
            ->select('COUNT(f.id)')
            ->join('deal.feedbacks', 'f')
            ->join('deal.company', 'dc')
            ->where("STR_TO_DATE(CONCAT_WS(' ', deal.endDate, deal.endTime), '%Y-%m-%d %H:%i:%s') > :endDate AND f.content is not null")
            ->andWhere('dc.cityId = :city')
            ->having('COUNT(f.id) > 5')
            ->setParameters(
                [
                    'endDate' => new \DateTime('now'),
                    'city' => $cityId
                ]
            )
            ->groupBy('deal.id')
        ;

         if ($search) {
             $query->andWhere(
                 $query->expr()->orx(
                     $query->expr()->like('deal.name', ':search'),
                     $query->expr()->like('deal.description', ':search'),
                     $query->expr()->like('deal.terms', ':search')
                 )
             )->setParameter('search', "%{$search}%");
         }

        return count($query->getQuery()->getResult());
    }

    public function getDealsWithFeedbacksCount($cityId, $search = null)
    {
        $query = $this->createQueryBuilder('deal')
            ->join('deal.feedbacks', 'f')
            ->join('deal.company', 'dc')
            ->where("STR_TO_DATE(CONCAT_WS(' ', deal.endDate, deal.endTime), '%Y-%m-%d %H:%i:%s') > :endDate AND f.content is not null")
            ->andWhere('dc.cityId = :city')
            ->setParameters(
                [
                    'endDate' => new \DateTime('now'),
                    'city' => $cityId
                ]
            )
        ;

        if ($search) {
            $query->andWhere(
                $query->expr()->orx(
                    $query->expr()->like('deal.name', ':search'),
                    $query->expr()->like('deal.description', ':search'),
                    $query->expr()->like('deal.terms', ':search')
                 )
            )->setParameter('search', "%{$search}%");
        }

        return count($query->getQuery()->getResult());
    }
}
