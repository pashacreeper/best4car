<?php

namespace Sto\CoreBundle\Entity\Catalog;

use Doctrine\ORM\Mapping as ORM;

/**
 * Modification
 *
 * @ORM\Entity()
 */
class Modification extends Base
{
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

    public function __toString()
    {
        return $this->getParent() . " - " . $this->getName();
    }
}
