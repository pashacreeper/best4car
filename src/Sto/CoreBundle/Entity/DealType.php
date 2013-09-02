<?php

namespace Sto\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Deal
 *
 * @ORM\Entity()
 *@ORM\Table(name="deal_types")
 */
class DealType
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="short_name", type="string", length=15, nullable=true)
     */
    private $shortName;

    /**
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="DealType", mappedBy="parent")
     */
    private $children;

    /**
     * @ORM\ManyToOne(targetEntity="DealType", inversedBy="children")
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
     * @ORM\OneToMany(targetEntity="\Sto\CoreBundle\Entity\Deal", mappedBy="type", cascade={"persist"})
     */
    protected $deals;

    public function __construct()
    {
        $this->deals = new ArrayCollection();
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
     * @param  string $code
     * @return DealType
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
     * @param  string $name
     * @return DealType
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
     * @return DealType
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
    public function setParent(DealType $parent = null)
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
    public function addChildren(DealType $children)
    {
        $this->children[] = $children;

        return $this;
    }

    /**
     * Remove children
     */
    public function removeChildren(DealType $children)
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

    public function addDeal(\Sto\CoreBundle\Entity\Deal $deal)
    {
        $this->deals[] = $deal;

        return $this;
    }

    public function removeDeal(\Sto\CoreBundle\Entity\Deal $deal)
    {
        $this->deals->removeElement($deal);

        return $this;
    }

    public function getDeals()
    {
        return $this->deals->toArray();
    }
}
