<?php

namespace Sto\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Orx;

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

    public function getCompanyGallery($company)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb
            ->select('gallery')
            ->from('StoCoreBundle:CompanyGallery', 'gallery')
            ->where('gallery.visible = :visible')
            ->andWhere('gallery.company = :company')
            ->setParameter('visible', 1)
            ->setParameter('company', $company)
        ;

        return $qb->getQuery()->execute();
    }

    /**
     * @param  array $params
     * @return array
     */
    public function getCompanyIdsWithFilter($params = [])
    {
        $qb = $this->createQueryBuilder('company')
            ->select('DISTINCT company.id')
            ->leftJoin('company.specializations', 'csp')
            ->leftJoin('csp.type', 'csp_type')
            ->leftJoin('csp.subType', 'csp_sub_type')
            ->leftJoin('company.feedbacks', 'fb')
            ->leftJoin('csp.services', 'services')
            ->leftJoin('services.service', 'auto_services')
            ->leftJoin('company.additionalServices', 'casp')
            ->leftJoin('company.autos', 'mark')
            ->where('company.visible = true')
            ->groupBy('company.id')
        ;

        if (isset($params['city']) && $params['city']) {
            $qb->andWhere('company.city = :city')
               ->setParameter('city', $params['city'])
            ;
        }

        if (isset($params['companyType']) && $params['companyType']) {
            $qb->andWhere('csp.type = :sp')
               ->setParameter('sp', $params['companyType'])
            ;
        }

        if (isset($params['subCompanyType']) && $params['subCompanyType']) {
            $qb->andWhere('csp.subType = :s')
               ->setParameter('s', $params['subCompanyType'])
            ;
        }

        if (isset($params['auto']) && $params['auto']) {
            $qb->join('company.autos', 'ca')
               ->andWhere('ca.id = :auto_id')
               ->setParameter('auto_id', $params['auto'])
            ;
        }

        if (isset($params['deals']) && $params['deals']) {
            $qb->join('company.deals', 'd')
               ->andWhere('d.endDate > :endDate')
               ->setParameter('endDate', new \DateTime('now'))
            ;
        } else {
            $qb->leftJoin('company.deals', 'd');
        }

        if (isset($params['rating']) && $params['rating']) {
            $qb->andWhere('company.rating BETWEEN :rating-0.01 AND 10.01')
               ->setParameter('rating', $params['rating'])
            ;
        }

        if (isset($params['additionalServices']) && $params['additionalServices']) {
            $qb->join('company.additionalServices', 'ads');
            foreach ($params['additionalServices'] as $k => $name) {
                $qb->andWhere("ads.shortName = :name_{$k}")
                   ->setParameter("name_{$k}", $name)
                ;
            }
        }

        if (isset($params['search']) && $params['search']) {
            $words = explode(" ", $params['search']);
            $i = 0;
            foreach ($words as $word) {
                if (strlen($word) < 3) {
                    continue;
                }
                $i++;
                $qb->andWhere(
                    $qb->expr()->orx(
                        $qb->expr()->like('company.name', ":search_$i"),
                        $qb->expr()->like('company.fullName', ":search_$i"),
                        $qb->expr()->like('company.description', ":search_$i"),
                        $qb->expr()->like('company.slogan', ":search_$i"),
                        $qb->expr()->like('csp_type.name', ":search_$i"),
                        $qb->expr()->like('auto_services.name', ":search_$i"),
                        $qb->expr()->like('casp.name', ":search_$i"),
                        $qb->expr()->like('mark.name', ":search_$i")
                ))->setParameter("search_$i", "%{$word}%");
            }
        }

        if (isset($params['time'])) {
            $qb->join('company.workingTime', 'cwt');

            if (in_array('24hours', $params['time'])) {
                $qb->andWhere("cwt.fromTime < '01:00:00' AND cwt.tillTime > '23:00:00'  AND cwt.daysMonday = 1 AND cwt.daysTuesday = 1 AND cwt.daysWednesday = 1 AND cwt.daysThursday = 1 AND cwt.daysFriday = 1 AND cwt.daysSaturday = 1 AND cwt.daysSunday = 1");
            }

            if (in_array('late', $params['time'])) {
                $qb->andWhere("cwt.tillTime >= '21:00:00' AND cwt.daysMonday = 1 AND cwt.daysTuesday = 1 AND cwt.daysWednesday = 1 AND cwt.daysThursday = 1 AND cwt.daysFriday = 1");
            }

            if (in_array('weekends', $params['time'])) {
                $qb->andWhere('cwt.daysSaturday = 1 OR cwt.daysSunday = 1');
            }
        }

        $result = $qb->getQuery()->getResult();
        $ids = [];

        foreach ($result as $value) {
            $ids[] = $value['id'];
        }

        return $ids;
    }

    /**
     * @param  array $params
     * @return array
     */
    public function getCompaniesWithFilterForMap($params = [])
    {
        $ids = $this->getCompanyIdsWithFilter($params);

        if (empty($ids)) {
            return [];
        }

        $qb = $this->createQueryBuilder('company')
            ->select('company.id, company.name, company.gps, company.vip, IDENTITY(company.type) as type')
            ->where('company.id IN(:ids)')
            ->setParameter('ids', $ids)
        ;

        return $qb->getQuery()->getResult();
    }

    /**
     * @param  array $params
     * @return array
     */
    public function getCompaniesWithFilterForList($params = [])
    {
        $ids = $this->getCompanyIdsWithFilter($params);

        if (empty($ids)) {
            return [];
        }

        $qb = $this->createQueryBuilder('company')
            ->select('company, d, cwt')
            ->leftJoin('company.specializations', 'csp')
            ->leftJoin('csp.type', 'csp_type')
            ->leftJoin('csp.subType', 'csp_sub_type')
            ->leftJoin('company.feedbacks', 'fb')
            ->leftJoin('company.deals', 'd')
            ->leftJoin('company.additionalServices', 'additional_services')
            ->leftJoin('company.workingTime', 'cwt')
            ->where('company.id IN(:ids)')
            ->setParameter('ids', $ids)
            ->groupBy('company.id')
        ;

        if ($params['sort'] == 'price') {
            $qb->orderBy('company.hourPrice', 'ASC');
        } else {
            $qb->orderBy('company.rating', 'DESC');
        }

        return $qb->getQuery()->getResult();
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
