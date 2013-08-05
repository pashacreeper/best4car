<?php

namespace Sto\CoreBundle\Entity\Dictionary;

use Doctrine\ORM\Mapping as ORM;

/**
 * AutoServices
 *
 * @ORM\Entity()
 */
class AutoServices extends Base
{
    /**
     * @ORM\Column(name="code", type="string", length=255, nullable=true)
     */
    private $code;

    /**
     * @ORM\ManyToMany(targetEntity="\Sto\CoreBundle\Entity\Dictionary\CompanyType", cascade={"persist"})
     * @ORM\JoinTable(name="company_type_auto_service",
     *     joinColumns={@ORM\JoinColumn(name="auto_service_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="company_type_id", referencedColumnName="id")}
     * )
     */
    private $companyType;

    /**
     * @ORM\ManyToMany(targetEntity="\Sto\CoreBundle\Entity\Company")
     */
    private $companies;

    /**
     * @ORM\ManyToMany(targetEntity="\Sto\CoreBundle\Entity\Deal")
     */
    private $deals;

    public function __construct()
    {
        parent::__construct();
        $this->companyType = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function setCode($value)
    {
        $this->code = $value;

        return $this;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setCompanyType($value)
    {
        $this->companyType = $value;

        return $this;
    }

    public function getCompanyType()
    {
        return $this->companyType;
    }

    public function addCompanyType($value)
    {
        $this->companyType[] = $value;

        return $this;
    }

    public function removeCompanyType($value)
    {
        $this->companyType->removeElement($value);

        return $this;
    }
}
