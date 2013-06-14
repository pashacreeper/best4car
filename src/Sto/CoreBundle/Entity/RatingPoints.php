<?php

namespace Sto\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RatingPoints
 *
 * @ORM\Table(name="rating_points")
 * @ORM\Entity
 */
class RatingPoints
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="point_name", type="string", length=50)
     */
    private $pointName;

    /**
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(name="value", type="float")
     */
    private $value;

    /**
     * Get id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set pointName
     */
    public function setPointName($pointName)
    {
        $this->pointName = $pointName;

        return $this;
    }

    /**
     * Get pointName
     */
    public function getPointName()
    {
        return $this->pointName;
    }

    /**
     * Set description
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set value
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     */
    public function getValue()
    {
        return $this->value;
    }
}
