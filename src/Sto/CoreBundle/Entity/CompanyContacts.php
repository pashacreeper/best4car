<?php

namespace Sto\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Company Contacts
 *
 * @ORM\Entity
 * @ORM\Table(name="company_contacts")
 */
class CompanyContacts
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
     * @ORM\Column(name="value", type="string")
     */
    private $value;

    /**
     * @var integer
     * @ORM\Column(name="company_id", type="integer", nullable=true)
     */
    private $companyId;

    /**
     * @ORM\ManyToOne(targetEntity="\Sto\CoreBundle\Entity\Company", inversedBy="contacts")
     * @ORM\JoinColumn(name="company_id", referencedColumnName="id")
     */
    private $company;

    /**
     * @var integer
     * @ORM\Column(name="type_id", type="integer", nullable=true)
     */
    private $typeId;
    /**
     * @ORM\ManyToOne(targetEntity="Sto\CoreBundle\Entity\Dictionary\ContactType")
     * @ORM\JoinColumn(name="type_id", referencedColumnName="id")
     */
    private $type;

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
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set companyId
     *
     * @param  integer     $companyId
     * @return RatingGroup
     */
    public function setCompanyId($companyId)
    {
        $this->companyId = $companyId;

        return $this;
    }

    /**
     * Get companyId
     *
     * @return integer
     */
    public function getCompanyId()
    {
        return $this->companyId;
    }

    /**
     * Add company
     *
     * @param  \Sto\CoreBundle\Entity\Company $company
     * @return RatingGroup
     */
    public function setCompany(\Sto\CoreBundle\Entity\Company $company)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Get company
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCompany()
    {
        return $this->company;
    }

    public function getTypeId()
    {
        return $this->typeId;
    }

    public function setTypeId($typeId)
    {
        $this->typeId = $typeId;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getType()
    {
        return $this->type;
    }

    public function setType(\Sto\CoreBundle\Entity\Dictionary\ContactType $type)
    {
        $this->type = $type;

        return $this;
    }

    public function __toString()
    {
        return "{$this->type}: {$this->value}";
    }
}
