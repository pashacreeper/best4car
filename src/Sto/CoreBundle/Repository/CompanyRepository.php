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

    /**
     * @param  array $params
     * @return array
     */
    public function getCompaniesWithFilter($params = array())
    {
        $qb = $this->createQueryBuilder('company')
            ->select('company, csp, fb, d, csp_type, csp_sub_type')
            ->leftJoin('company.specializations', 'csp')
            ->leftJoin('csp.type', 'csp_type')
            ->leftJoin('csp.subType', 'csp_sub_type')
            ->leftJoin('company.feedbacks', 'fb')
            ->where('company.visible = true')
        ;

        if (isset($params['city']) && $params['city']) {
            $qb->andWhere('company.city = :city')
                ->setParameter('city', $params['city']);
        }

        if (isset($params['companyType']) && $params['companyType']) {
            $qb->andWhere('csp.type = :sp')
                ->setParameter('sp', $params['companyType']);
        }

        if (isset($params['subCompanyType']) && $params['subCompanyType']) {
            $qb->andWhere('csp.subType = :s')
                ->setParameter('s', $params['subCompanyType']);
        }

        if (isset($params['auto']) && $params['auto']) {
            $qb->join('company.autos', 'ca')
                ->andWhere('ca.id = :auto_id')
                ->setParameter('auto_id', $params['auto']);
        }

        if (isset($params['deals']) && $params['deals']) {
            $qb->join('company.deals', 'd')
                ->andWhere('d.endDate > :endDate')
                ->setParameter('endDate', new \DateTime('now'));
        } else {
            $qb->leftJoin('company.deals', 'd');
        }

        if (isset($params['rating']) && $params['rating']) {
            $qb->andWhere('company.rating BETWEEN :rating-0.01 AND 10.01')
                ->setParameter('rating', $params['rating']);
        }

        if (isset($params['additionalServices']) && $params['additionalServices']) {
            $qb->join('company.additionalServices', 'ads');
            foreach ($params['additionalServices'] as $k => $name) {
                $qb->andWhere("ads.shortName = :name_{$k}")
                    ->setParameter("name_{$k}", $name);
            }
        }

        if (isset($params['search']) && $params['search']) {
            $words = explode(" ", $params['search']);
            foreach ($words as $word) {
                $qb->andWhere(
                    $qb->expr()->orx(
                        $qb->expr()->like('company.name', ':search'),
                        $qb->expr()->like('company.fullName', ':search'),
                        $qb->expr()->like('company.description', ':search'),
                        $qb->expr()->like('company.slogan', ':search'),
                        $qb->expr()->like('cs.name', ':search')
                    )
                )->setParameter('search', "%{$word}%");
            }
        }

        if ($params['sort'] == 'price') {
            $qb->orderBy('company.hourPrice', 'ASC');
        } else {
            $qb->orderBy('company.rating', 'DESC');
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
