<?php
namespace Sto\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sto\CoreBundle\Entity\Company;
use Sto\CoreBundle\Entity\CompanyType;

/**
 * @ORM\Entity(repositoryClass="Sto\CoreBundle\Repository\CompanySpecializationRepository")
 * @ORM\Table(name="company_specializations")
 */
class CompanySpecialization
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Sto\CoreBundle\Entity\Company", inversedBy="specializations")
     * @ORM\JoinColumn(name="company_id", referencedColumnName="id")
     */
    private $company;

    /**
     * @ORM\ManyToOne(targetEntity="Sto\CoreBundle\Entity\CompanyType")
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity="Sto\CoreBundle\Entity\CompanyType")
     */
    private $subType;

    /**
     * @ORM\OneToMany(targetEntity="CompanyAutoService", mappedBy="specialization", cascade={"all"})
     */
    private $services;

    public function __toString()
    {
        if ($this->type && $this->subType) {
            return sprintf('%s - %s', $this->type->getName(), $this->subType->getName());
        }

        return (string) $this->id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setid($value)
    {
        $this->id = $value;

        return $this;
    }

    public function getCompany()
    {
        return $this->company;
    }

    public function setCompany(Company $value)
    {
        $this->company = $value;
        if ($this->company) {
            $this->company->setTypeFromSpecs();
        }

        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType(CompanyType $value)
    {
        $this->type = $value;
        if ($this->company) {
            $this->company->setTypeFromSpecs();
        }

        return $this;
    }

    public function getSubType()
    {
        return $this->subType;
    }

    public function setSubType(CompanyType $value)
    {
        $this->subType = $value;

        return $this;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->services = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add services
     *
     * @param  \Sto\CoreBundle\Entity\CompanyAutoService $services
     * @return CompanySpecialization
     */
    public function addService(\Sto\CoreBundle\Entity\CompanyAutoService $services)
    {
        $this->services[] = $services;

        return $this;
    }

    /**
     * Remove services
     *
     * @param \Sto\CoreBundle\Entity\CompanyAutoService $services
     */
    public function removeService(\Sto\CoreBundle\Entity\CompanyAutoService $services)
    {
        $this->services->removeElement($services);
    }

    /**
     * Get services
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getServices()
    {
        return $this->services;
    }
}
