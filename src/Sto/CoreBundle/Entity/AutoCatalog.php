<?php

namespace Sto\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AutoCatalog
 *
 * @ORM\Table(name="autocatalog")
 * @ORM\Entity(repositoryClass="Sto\CoreBundle\Repository\AutoCatalogRepository")
 */
class AutoCatalog
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
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="AutoCatalog", mappedBy="parent" , cascade={"remove"})
     */
    private $children;

    /**
     * @ORM\ManyToOne(targetEntity="AutoCatalog", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id" )
     */
    private $parent;

    /**
     * @var integer
     *
     * @ORM\Column(name="parent_id", type="integer", nullable=true)
     */
    private $parentId;

    /**
     * @var boolean
     * @ORM\Column(name="visible", type="boolean")
     */
    private $visible;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
        $this->visible = true;
    }

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
     * @param  string     $name
     * @return AutoCatalog
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set parentId
     *
     * @param  integer    $parentId
     * @return AutoCatalog
     */
    public function setParentId($parentId)
    {
        $this->parentId = $parentId;

        return $this;
    }

    /**
     * Get parentId
     *
     * @return integer
     */
    public function getParentId()
    {
        return $this->parentId;
    }

    /**
     * Set parent
     *
     */
    public function setParent(AutoCatalog $parent = null)
    {
        $this->parent = $parent;
        if ($parent != null) {
            $this->parentId = $parent->getId();
        }

        return $this;
    }

    /**
     * Get parent
     *
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add children
     *
     */
    public function addChildren(AutoCatalog $children)
    {
        $this->children[] = $children;

        return $this;
    }

    /**
     * Remove children
     *
     */
    public function removeChildren(AutoCatalog $children)
    {
        $this->children->removeElement($children);
    }

    /**
     * Get children
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getChildren()
    {
        return $this->children;
    }

    public function isVisible()
    {
        return $this->visible;
    }

    public function setVisible($visible)
    {
        $this->visible = $visible;

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}
