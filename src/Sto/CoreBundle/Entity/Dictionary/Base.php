<?php

namespace Sto\CoreBundle\Entity\Dictionary;

use Doctrine\ORM\Mapping as ORM;

/**
 * Base
 *
 * @ORM\Entity(repositoryClass="Sto\CoreBundle\Repository\DictionaryRepository")
 * @ORM\Table(name="dictionaries")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({
 *     "company_type"       = "Company",
 *     "deals_type"         = "Deal",
 *     "additional_service" = "AdditionalService",
 *     "work"               = "Work",
 *     "currency"           = "Currency",
 *     "country"            = "Country",
 *     "city"               = "City"
 * })
 */
class Base
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="short_name", type="string", length=5, nullable=true)
     */
    private $shortName;

    /**
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="Base", mappedBy="parent")
     */
    private $children;

    /**
     * @ORM\ManyToOne(targetEntity="Base", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    private $parent;

    /**
     * @var integer
     *
     * @ORM\Column(name="parent_id", type="integer", nullable=true)
     */
    private $parentId;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @param  string     $code
     * @return Base
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
     * @param  string     $name
     * @return Base
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
     * @param  integer    $parentId
     * @return Base
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
    public function setParent(Base $parent = null)
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
    public function addChildren(Base $children)
    {
        $this->children[] = $children;

        return $this;
    }

    /**
     * Remove children
     */
    public function removeChildren(Base $children)
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
}
