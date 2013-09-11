<?php
namespace Sto\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sto\CoreBundle\Entity\Company;
use Sto\CoreBundle\Entity\CompanyType;

/**
 * @ORM\Entity()
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

        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType(CompanyType $value)
    {
        $this->type = $value;

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
}