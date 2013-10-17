<?php

namespace Sto\CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * AutoServices
 *
 * @ORM\Entity()
 * @ORM\Table(name="auto_services", indexes={@ORM\Index(name="auto_services_search_idx", columns={"name"})})
 */
class AutoServices
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="CompanyType")
     * @ORM\JoinColumn(name="company_type_id", referencedColumnName="id", nullable=false)
     */
    private $companyType;

    /**
     * @ORM\Column(name="short_name", type="string", length=15, nullable=true)
     */
    private $shortName;

    /**
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="AutoServices", mappedBy="parent")
     */
    private $children;

    /**
     * @ORM\ManyToOne(targetEntity="AutoServices", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    private $parent;

    /**
     * @ORM\Column(name="parent_id", type="integer", nullable=true)
     */
    private $parentId;

    /**
     * @ORM\Column(name="position", type="integer", nullable=true)
     */
    private $position;

    /**
     * @ORM\Column(name="code", type="string", length=255, nullable=true)
     */
    private $code;

    /**
     * @ORM\ManyToMany(targetEntity="\Sto\CoreBundle\Entity\Company")
     */
    private $companies;

    /**
     * @ORM\ManyToMany(targetEntity="\Sto\CoreBundle\Entity\Deal")
     */
    private $deals;

    /**
     * @ORM\OneToMany(targetEntity="CompanyAutoService", mappedBy="service", cascade={"all"})
     */
    private $companyServices;

    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->companyType = new ArrayCollection();
        $this->companyServices = new ArrayCollection();
    }

    /**
     * Get id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set code
     *
     * @param  string       $code
     * @return AutoServices
     */
    public function setShortName($shortName)
    {
        $this->shortName = $shortName;

        return $this;
    }

    /**
     * Get name
     */
    public function getShortName()
    {
        return $this->shortName;
    }

    /**
     * Set name
     *
     * @param  string       $name
     * @return AutoServices
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set parentId
     *
     * @param  integer      $parentId
     * @return AutoServices
     */
    public function setParentId($parentId)
    {
        $this->parentId = $parentId;

        return $this;
    }

    /**
     * Get parentId
     */
    public function getParentId()
    {
        return $this->parentId;
    }

    /**
     * Set parent
     */
    public function setParent(AutoServices $parent = null)
    {
        $this->parent = $parent;
        if ($parent != null) {
            $this->parentId = $parent->getId();
        }

        return $this;
    }

    /**
     * Get parent
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add children
     */
    public function addChildren(AutoServices $children)
    {
        $this->children[] = $children;

        return $this;
    }

    /**
     * Remove children
     */
    public function removeChildren(AutoServices $children)
    {
        $this->children->removeElement($children);

        return $this;
    }

    /**
     * Get children
     */
    public function getChildren()
    {
        return $this->children;
    }

    public function __toString()
    {
        return $this->name;
    }

    /**
     * set Position
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * get Position
     */
    public function getPosition()
    {
        return $this->position;
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

    public function getCompanyTypeName()
    {
        return $this->companyType->getId() ? $this->companyType->getName() : '';
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

    /**
     * Add children
     *
     * @param  \Sto\CoreBundle\Entity\AutoServices $children
     * @return AutoServices
     */
    public function addChild(\Sto\CoreBundle\Entity\AutoServices $children)
    {
        $this->children[] = $children;

        return $this;
    }

    /**
     * Remove children
     *
     * @param \Sto\CoreBundle\Entity\AutoServices $children
     */
    public function removeChild(\Sto\CoreBundle\Entity\AutoServices $children)
    {
        $this->children->removeElement($children);
    }

    /**
     * Add companies
     *
     * @param  \Sto\CoreBundle\Entity\Company $companies
     * @return AutoServices
     */
    public function addCompany(\Sto\CoreBundle\Entity\Company $companies)
    {
        $this->companies[] = $companies;

        return $this;
    }

    /**
     * Remove companies
     *
     * @param \Sto\CoreBundle\Entity\Company $companies
     */
    public function removeCompany(\Sto\CoreBundle\Entity\Company $companies)
    {
        $this->companies->removeElement($companies);
    }

    /**
     * Get companies
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCompanies()
    {
        return $this->companies;
    }

    /**
     * Add deals
     *
     * @param  \Sto\CoreBundle\Entity\Deal $deals
     * @return AutoServices
     */
    public function addDeal(\Sto\CoreBundle\Entity\Deal $deals)
    {
        $this->deals[] = $deals;

        return $this;
    }

    /**
     * Remove deals
     *
     * @param \Sto\CoreBundle\Entity\Deal $deals
     */
    public function removeDeal(\Sto\CoreBundle\Entity\Deal $deals)
    {
        $this->deals->removeElement($deals);
    }

    /**
     * Get deals
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDeals()
    {
        return $this->deals;
    }

    /**
     * Add companyServices
     *
     * @param \Sto\CoreBundle\Entity\CompanyAutoService $companyServices
     * @return AutoServices
     */
    public function addCompanyService(\Sto\CoreBundle\Entity\CompanyAutoService $companyServices)
    {
        $this->companyServices[] = $companyServices;

        return $this;
    }

    /**
     * Remove companyServices
     *
     * @param \Sto\CoreBundle\Entity\CompanyAutoService $companyServices
     */
    public function removeCompanyService(\Sto\CoreBundle\Entity\CompanyAutoService $companyServices)
    {
        $this->companyServices->removeElement($companyServices);
    }

    /**
     * Get companyServices
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCompanyServices()
    {
        return $this->companyServices;
    }
}
