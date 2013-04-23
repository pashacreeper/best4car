<?php

namespace Sto\CoreBundle\Entity\Catalog;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Base
 *
 * @ORM\Entity()
 * @ORM\Table(name="auto_catalog")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({
 *     "mark"         = "Mark",
 *     "model"        = "Model",
 *     "modification" = "Modification"
 * })
 */
class Base
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(name="name", type="string", length=255)
     */
    protected $name;

    /**
     * @ORM\OneToMany(targetEntity="Base", mappedBy="parent")
     */
    protected $children;

    /**
     * @ORM\ManyToOne(targetEntity="Base", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    protected $parent;

    /**
     * @ORM\Column(name="parent_id", type="integer", nullable=true)
     */
    protected $parentId;

    /**
     * @Assert\Url()
     * @ORM\Column(name="uri", type="string", length=255, nullable=true)
     */
    protected $uri;

    /**
     * @var boolean
     * @ORM\Column(name="visible", type="boolean")
     */
    protected $visible;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
        $this->visible = true;

        return $this;
    }

    /**
     * Get id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param  string $name
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
     * @param  integer $parentId
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

    /**
     * Set uri
     *
     * @param string $uri
     */
    public function setUri($uri)
    {
        $this->uri = $uri;

        return $this;
    }

    /**
     * Get uri
     */
    public function getUri()
    {
        return $this->uri;
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
