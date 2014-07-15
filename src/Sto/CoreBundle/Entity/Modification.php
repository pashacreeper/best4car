<?php

namespace Sto\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Modification
 *
 * @ORM\Entity(repositoryClass="Sto\CoreBundle\Repository\ModificationRepository")
 * @ORM\Table(name="catalog_modifications")
 */
class Modification
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="name", type="string", length=255)
     */
    protected $name;

    /**
     * @ORM\ManyToOne(targetEntity="Model", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    protected $parent;

    /**
     * @ORM\Column(name="parent_id", type="integer", nullable=true)
     */
    protected $parentId;

    /**
     * @ORM\Column(name="uri", type="string", length=255, nullable=true)
     */
    protected $uri;

    /**
     * @var boolean
     * @ORM\Column(name="visible", type="boolean")
     */
    protected $visible;

    /**
     * @ORM\Column(type="integer", name="number_of_doors", nullable=true)
     */
    private $numberOfDoors;

    /**
     * @ORM\Column(type="integer", name="engine", nullable=true)
     */
    private $engine;

    /**
     * @ORM\Column(type="integer", name="power", nullable=true)
     */
    private $power;

    /**
     * @ORM\Column(type="integer", name="full_speed", nullable=true)
     */
    private $fullSpeed;

    /**
     * @ORM\Column(type="string", length=255, name="body_type", nullable=true)
     */
    private $bodyType;

    /**
     * @ORM\Column(type="string", name="start_of_production", nullable=true)
     */
    private $startOfProduction;

    /**
     * @ORM\Column(type="string", name="closing_of_production", nullable=true)
     */
    private $closingOfProduction;

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
     * @param  string       $name
     * @return Modification
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
     * @return Model
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
    public function setParent(Model $parent = null)
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
        return $this->getName();
    }

    public function setNumberOfDoors($numberOfDoors)
    {
        $this->numberOfDoors = $numberOfDoors;

        return $this;
    }

    public function getNumberOfDoors()
    {
        return $this->numberOfDoors;
    }

    public function setEngine($engine)
    {
        $this->engine = $engine;

        return $this;
    }

    public function getEngine()
    {
        return $this->engine;
    }

    public function setPower($power)
    {
        $this->power = $power;

        return $this;
    }

    public function getPower()
    {
        return $this->power;
    }

    public function setFullSpeed($fullSpeed)
    {
        $this->fullSpeed = $fullSpeed;

        return $this;
    }

    public function getFullSpeed()
    {
        return $this->fullSpeed;
    }

    public function setBodyType($bodyType)
    {
        $this->bodyType = $bodyType;

        return $this;
    }

    public function getBodyType()
    {
        return $this->bodyType;
    }

    public function setStartOfProduction($startOfProduction)
    {
        $this->startOfProduction = $startOfProduction;

        return $this;
    }

    public function getStartOfProduction()
    {
        return $this->startOfProduction;
    }

    public function setClosingOfProduction($closingOfProduction)
    {
        $this->closingOfProduction = $closingOfProduction;

        return $this;
    }

    public function getClosingOfProduction()
    {
        return $this->closingOfProduction;
    }

    /**
     * Get visible
     *
     * @return boolean
     */
    public function getVisible()
    {
        return $this->visible;
    }

    public function getYearsOfProduction()
    {
        $start = $this->startOfProduction;
        $end = $this->closingOfProduction;
        if (!$end) {
            $end = '...';
        }

        return "$start - $end";
    }

    public function getNameWithYear()
    {
        return $this->getName() . ' - ' . $this->getYearsOfProduction();
    }
}
