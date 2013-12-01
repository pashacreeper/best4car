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
     * IDs of groups
     * TODO: use shrot names for same purpose.
     */
    const FROZEN = 1;
    const BLOCKED = 2;
    const USER = 3;
    const MANAGER = 4;
    const REDACTOR = 5;
    const MODERATOR = 6;
    const ADMINISTRATOR = 7;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
     protected $id;

     /**
     * @ORM\ManyToMany(targetEntity="Sto\UserBundle\Entity\User", mappedBy="groups")
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
