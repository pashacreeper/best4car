<?php

namespace Sto\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sto\UserBundle\Entity\UserCar;

/**
 * Modification
 *
 * @ORM\Entity()
 * @ORM\Table(name="custom_modifications")
 */
class CustomModification
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
     * @ORM\Column(name="engineType", type="string", length=255, nullable=true)
     */
    protected $engineType;

    /**
     * @var string
     *
     * @ORM\Column(name="engineModel", type="string", length=255, nullable=true)
     */
    protected $engineModel;

    /**
     * @var int
     *
     * @ORM\Column(name="engineVolume", type="integer", nullable=true)
     */
    protected $engineVolume;

    /**
     * @var int
     *
     * @ORM\Column(name="enginePower", type="integer", nullable=true)
     */
    protected $enginePower;

    /**
     * @var int
     *
     * @ORM\Column(name="fuelTypes", type="array", nullable=true)
     */
    protected $fuelTypes;

    /**
     * @var string
     *
     * @ORM\Column(name="wheelType", type="string", length=255, nullable=true)
     */
    protected $wheelType;

    /**
     * @var string
     *
     * @ORM\Column(name="bodyType", type="string", length=255, nullable=true)
     */
    protected $bodyType;

    /**
     * @ORM\OneToOne(targetEntity="Sto\UserBundle\Entity\UserCar")
     * @ORM\JoinColumn(name="car_id", referencedColumnName="id")
     */
    protected $userCar;

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getId();
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
     * Set engineType
     *
     * @param  string             $engineType
     * @return CustomModification
     */
    public function setEngineType($engineType)
    {
        $this->engineType = $engineType;

        return $this;
    }

    /**
     * Get engineType
     *
     * @return string
     */
    public function getEngineType()
    {
        return $this->engineType;
    }

    /**
     * Set engineModel
     *
     * @param  string             $engineModel
     * @return CustomModification
     */
    public function setEngineModel($engineModel)
    {
        $this->engineModel = $engineModel;

        return $this;
    }

    /**
     * Get engineModel
     *
     * @return string
     */
    public function getEngineModel()
    {
        return $this->engineModel;
    }

    /**
     * Set engineVolume
     *
     * @param  integer            $engineVolume
     * @return CustomModification
     */
    public function setEngineVolume($engineVolume)
    {
        $this->engineVolume = $engineVolume;

        return $this;
    }

    /**
     * Get engineVolume
     *
     * @return integer
     */
    public function getEngineVolume()
    {
        return $this->engineVolume;
    }

    /**
     * Set enginePower
     *
     * @param  integer            $enginePower
     * @return CustomModification
     */
    public function setEnginePower($enginePower)
    {
        $this->enginePower = $enginePower;

        return $this;
    }

    /**
     * Get enginePower
     *
     * @return integer
     */
    public function getEnginePower()
    {
        return $this->enginePower;
    }

    /**
     * Set fuelTypes
     *
     * @param  array              $fuelTypes
     * @return CustomModification
     */
    public function setFuelTypes($fuelTypes)
    {
        $this->fuelTypes = $fuelTypes;

        return $this;
    }

    /**
     * Get fuelTypes
     *
     * @return array
     */
    public function getFuelTypes()
    {
        return $this->fuelTypes;
    }

    /**
     * Set wheelType
     *
     * @param  string             $wheelType
     * @return CustomModification
     */
    public function setWheelType($wheelType)
    {
        $this->wheelType = $wheelType;

        return $this;
    }

    /**
     * Get wheelType
     *
     * @return string
     */
    public function getWheelType()
    {
        return $this->wheelType;
    }

    /**
     * Set bodyType
     *
     * @param  string             $bodyType
     * @return CustomModification
     */
    public function setBodyType($bodyType)
    {
        $this->bodyType = $bodyType;

        return $this;
    }

    /**
     * Get bodyType
     *
     * @return string
     */
    public function getBodyType()
    {
        return $this->bodyType;
    }

    /**
     * Set userCar
     *
     * @param  UserCar            $userCar
     * @return CustomModification
     */
    public function setUserCar(UserCar $userCar = null)
    {
        $this->userCar = $userCar;

        return $this;
    }

    /**
     * Get userCar
     *
     * @return UserCar
     */
    public function getUserCar()
    {
        return $this->userCar;
    }
}
