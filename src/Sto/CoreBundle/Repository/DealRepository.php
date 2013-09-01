<?php

namespace Sto\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;

/**
 * DealRepository
 */
class DealRepository extends EntityRepository
{
    public function getDealTypes($city_id, $search = null)
    {
        $dealsSearchCondition = "c.city_id = :city_id AND d.end_date > NOW()";
        if ($search) {
            $dealsSearchCondition .= " AND (d.name LIKE :search OR d.description LIKE :search)";
        }

        $sql = "
            SELECT dt.id, dt.name, COUNT(d.id) AS deals_count
            FROM dictionaries AS dt
            LEFT JOIN (
                SELECT d.id, d.type_id FROM deals AS d
                JOIN companies AS c ON (c.id = d.company_id)
                WHERE {$dealsSearchCondition}
            ) AS d ON (d.type_id = dt.id)
            WHERE
                dt.discr = 'deals_type'
            GROUP BY dt.id
            ORDER BY dt.position ASC
        ";

        $rsm = new ResultSetMapping;
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('name', 'name');
        $rsm->addScalarResult('deals_count', 'deals_count');

        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);
        $query->setParameter('city_id', $city_id);
        if ($search) {
            $query->setParameter('search', "%{$search}%");
        }

        return $query->getResult();
    }

    public function getDeals($city_id, $search = null)
    {
        $query = $this->createQueryBuilder('deal')
            ->join('deal.company', 'dc')
            ->where('deal.endDate > :endDate')
            ->andWhere('dc.cityId = :city')
            ->setParameters(
                [
                    'endDate' => new \DateTime('now'),
                    'city' => $city_id
                ]
            );

        if ($search) {
            $query->andWhere(
                $query->expr()->orx(
                    $query->expr()->like('deal.name', ':search'),
                    $query->expr()->like('deal.description', ':search')
                )
            )->setParameter('search', "%{$search}%");
        }

        return $query->getQuery()->getResult();
    }

    public function getVipDeals($city_id)
    {
        $query = $this->createQueryBuilder('deal')
            ->join('deal.company', 'dc')
            ->where('deal.endDate > :endDate')
            ->andWhere('dc.cityId = :city')
            ->andWhere('deal.is_vip = 1')
            ->setParameters(
                [
                    'endDate' => new \DateTime('now'),
                    'city' => $city_id
                ]
            )
            ->getQuery();

        return $query->getResult();
    }

    public function getPopularDealsCount($city_id)
    {
        $query = $this->createQueryBuilder('deal')
            ->select('COUNT(f.id)')
            ->join('deal.feedbacks', 'f')
            ->join('deal.company', 'dc')
            ->where('deal.endDate > :endDate AND f.content is not null')
            ->andWhere('dc.cityId = :city')
            ->having('COUNT(f.id) > 5')
            ->setParameters(
                [
                    'endDate' => new \DateTime('now'),
                    'city' => $city_id
                ]
            )
            ->groupBy('deal.id')
            ->getQuery();

        return count($query->getResult());
    }

    public function getDealsWithFeedbacksCount($city_id)
    {
        $query = $this->createQueryBuilder('deal')
            ->join('deal.feedbacks', 'f')
            ->join('deal.company', 'dc')
            ->where('deal.endDate > :endDate AND f.content is not null')
            ->andWhere('dc.cityId = :city')
            ->setParameters(
                [
                    'endDate' => new \DateTime('now'),
                    'city' => $city_id
                ]
            )
            ->getQuery();

        return count($query->getResult());
    }
}
