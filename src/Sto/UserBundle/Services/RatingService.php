<?php
namespace Sto\UserBundle\Services;

use Doctrine\ORM\EntityManager;
use Sto\UserBundle\Entity\RatingGroup;

Class RatingService
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function getRatingGroupByRating($rating)
    {
        $groups = $this->em->getRepository('StoUserBundle:RatingGroup')
            ->createQueryBuilder('rg')
            ->where('rg.minRating <= :rating AND rg.maxRating > :rating')
            ->setParameter('rating', $rating)
            ->getQuery()
            ->getResult()
            ;

        if (count($groups) > 0) {
            foreach ($groups as $value) {
                return $value;
            }
        } else

            return null;
    }
}
