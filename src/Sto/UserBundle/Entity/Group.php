<?php

namespace Sto\UserBundle\Entity;

use FOS\UserBundle\Model\Group as BaseGroup;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="groups")
 */
class Group extends BaseGroup
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
     protected $id;

     /**
     * @ORM\ManyToMany(targetEntity="Sto\UserBundle\Entity\User", mappedBy="groups", cascade={"all"})
     *
     */
    protected $users;

    /**
     * @ORM\ManyToMany(targetEntity="Sto\CoreBundle\Entity\Company", mappedBy="groups")
     */
    protected $companies;

    public function __toString()
    {
        return $this->getName();
    }

    public function getUsers()
    {
        return $this->users;
    }

    public function getCompanies()
    {
        return $this->companies;
    }
}
