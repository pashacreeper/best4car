<?php
namespace Sto\UserBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    /**
     * Finding user by email or by name
     * @param  string $login
     * @return [type] [description]
     */
    public function findUserByNameOrByEmail($login)
    {
        $qb = $this->createQueryBuilder('user');
        $qb
            ->where('user.username = :login')
            ->orWhere('user.email = :login')
            ->setParameter('login', $login)
        ;

        return $qb->getQuery()->getOneOrNullResult();
    }
}
