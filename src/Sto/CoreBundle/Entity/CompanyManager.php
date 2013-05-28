<?php

namespace Sto\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sto\CoreBundle\Entity\Company;
use Sto\UserBundle\Entity\User;

/**
 * CompanyManager
 *
 * @ORM\Table(name="company_manager")
 * @ORM\Entity(repositoryClass="Sto\CoreBundle\Repository\CompanyManagerRepository")
 */
class CompanyManager
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="phone", type="string")
     */
    private $phone;

    /**
     * @ORM\ManyToOne(targetEntity="\Sto\UserBundle\Entity\User", inversedBy="companyManager")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="Sto\CoreBundle\Entity\Company", inversedBy="companyManager", cascade={"persist"})
     * @ORM\JoinColumn(name="company_id", referencedColumnName="id")
     */
    private $company;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param  string      $name
     * @return RatingGroup
     */
    public function setPhone($value)
    {
        $this->phone = $value;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set userId
     *
     * @param  integer     $userId
     * @return RatingGroup
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->userId;
    }

    public function setUser(\Sto\UserBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getCompany()
    {
        return $this->company;
    }

    public function setCompany(\Sto\CoreBundle\Entity\Company $company)
    {
        $this->company = $company;

        return $this;
    }
}