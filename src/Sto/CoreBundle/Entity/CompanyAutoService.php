<?php
namespace Sto\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Sto\CoreBundle\Repository\CompanyAutoServiceRepository")
 * @ORM\Table(name="company_services")
 */
class CompanyAutoService
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Sto\CoreBundle\Entity\AutoServices", inversedBy="companyServices")
     * @ORM\JoinColumn(name="service_id", referencedColumnName="id")
     */
    private $service;

    /**
     * @ORM\ManyToOne(targetEntity="Sto\CoreBundle\Entity\CompanySpecialization", inversedBy="services", cascade={"persist"})
     * @ORM\JoinColumn(name="specialization_id", referencedColumnName="id")
     */
    private $specialization;

    /**
     * @ORM\OneToMany(targetEntity="CompanyAutoService", mappedBy="parent", cascade={"all"})
     */
    private $children;

    /**
     * @ORM\ManyToOne(targetEntity="CompanyAutoService", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    private $parent;

    public function getId()
    {
        return $this->id;
    }

    public function __toString()
    {
        return $this->service->getName();
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set service
     *
     * @param  \Sto\CoreBundle\Entity\AutoServices $service
     * @return CompanyAutoService
     */
    public function setService(\Sto\CoreBundle\Entity\AutoServices $service = null)
    {
        $this->service = $service;

        return $this;
    }

    /**
     * Get service
     *
     * @return \Sto\CoreBundle\Entity\AutoServices
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * Set specialization
     *
     * @param  \Sto\CoreBundle\Entity\CompanySpecialization $specialization
     * @return CompanyAutoService
     */
    public function setSpecialization(\Sto\CoreBundle\Entity\CompanySpecialization $specialization = null)
    {
        $this->specialization = $specialization;

        return $this;
    }

    /**
     * Get specialization
     *
     * @return \Sto\CoreBundle\Entity\CompanySpecialization
     */
    public function getSpecialization()
    {
        return $this->specialization;
    }

    /**
     * Add children
     *
     * @param  \Sto\CoreBundle\Entity\CompanyAutoService $children
     * @return CompanyAutoService
     */
    public function addChild(\Sto\CoreBundle\Entity\CompanyAutoService $children)
    {
        $this->children[] = $children;

        return $this;
    }

    /**
     * Remove children
     *
     * @param \Sto\CoreBundle\Entity\CompanyAutoService $children
     */
    public function removeChild(\Sto\CoreBundle\Entity\CompanyAutoService $children)
    {
        $this->children->removeElement($children);
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set parent
     *
     * @param  \Sto\CoreBundle\Entity\CompanyAutoService $parent
     * @return CompanyAutoService
     */
    public function setParent(\Sto\CoreBundle\Entity\CompanyAutoService $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \Sto\CoreBundle\Entity\CompanyAutoService
     */
    public function getParent()
    {
        return $this->parent;
    }
}
