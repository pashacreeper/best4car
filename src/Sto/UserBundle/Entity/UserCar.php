<?php

namespace Sto\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * User Cars
 *
 * @ORM\Entity
 * @ORM\Table(name="user_cars")
 */
class UserCar
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
     * @ORM\ManyToOne(targetEntity="\Sto\UserBundle\Entity\User", inversedBy="contacts")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="Sto\CoreBundle\Entity\Mark", inversedBy="userCars")
     * @ORM\JoinColumn(name="mark_id", referencedColumnName="id")
     */
    private $mark;

    /**
     * @ORM\ManyToOne(targetEntity="Sto\CoreBundle\Entity\Model", inversedBy="userCars")
     * @ORM\JoinColumn(name="model_id", referencedColumnName="id")
     */
    private $model;

    /**
     * @ORM\ManyToOne(targetEntity="Sto\CoreBundle\Entity\Modification", inversedBy="userCars")
     * @ORM\JoinColumn(name="modification_id", referencedColumnName="id")
     */
    private $modification;

    /**
     * @var string
     *
     * @ORM\Column(name="transmission", type="string", length=255, nullable=true)
     */
    protected $transmission;

    /**
     * @var string
     *
     * @ORM\Column(name="vin", type="string", length=255, nullable=true)
     */
    protected $vin;

    /**
     * @var string
     *
     * @ORM\Column(name="drive2", type="string", length=255, nullable=true)
     */
    protected $drive2;

    /**
     * @ORM\OneToMany(targetEntity="UserCarImage", mappedBy="car", cascade={"all"})
     */
    private $images;

    public function __construct()
    {
        $this->images = new ArrayCollection();
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
     * Add user
     *
     * @param  \Sto\UserBundle\Entity\User $user
     * @return RatingGroup
     */
    public function setUser(\Sto\UserBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set mark
     *
     * @param  \Sto\CoreBundle\Entity\Mark $mark
     * @return UserCar
     */
    public function setMark(\Sto\CoreBundle\Entity\Mark $mark)
    {
        $this->mark = $mark;

        return $this;
    }

    /**
     * Get mark
     *
     * @return \Sto\CoreBundle\Entity\Mark
     */
    public function getMark()
    {
        return $this->mark;
    }

    /**
     * Set model
     *
     * @param  \Sto\CoreBundle\Entity\Model $model
     * @return UserCar
     */
    public function setModel(\Sto\CoreBundle\Entity\Model $model)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Get model
     *
     * @return \Sto\CoreBundle\Entity\Model
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Set modification
     *
     * @param  \Sto\CoreBundle\Entity\Modification $modification
     * @return UserCar
     */
    public function setModification(\Sto\CoreBundle\Entity\Modification $modification)
    {
        $this->modification = $modification;

        return $this;
    }

    /**
     * Get modification
     *
     * @return \Sto\CoreBundle\Entity\Modification
     */
    public function getModification()
    {
        return $this->modification;
    }

    /**
     * Set transmission
     *
     * @param  string $transmission
     * @return UserCar
     */
    public function setTransmission($transmission)
    {
        $this->transmission = $transmission;

        return $this;
    }

    /**
     * Get transmission
     *
     * @return string
     */
    public function getTransmission()
    {
        return $this->transmission;
    }

    /**
     * Set vin
     *
     * @param  string $vin
     * @return UserCar
     */
    public function setVin($vin)
    {
        $this->vin = $vin;

        return $this;
    }

    /**
     * Get vin
     *
     * @return string
     */
    public function getVin()
    {
        return $this->vin;
    }

    /**
     * Set drive2
     *
     * @param  string $drive2
     * @return UserCar
     */
    public function setDrive2($drive2)
    {
        $this->drive2 = $drive2;

        return $this;
    }

    /**
     * Get drive2
     *
     * @return string
     */
    public function getDrive2()
    {
        return $this->drive2;
    }

    public function getName()
    {
        return $this->getMark(). ' '.$this->getModel();
    }

    public function getTransmissionName()
    {
        if(!$this->transmission) {
            return "";
        }

        return strtoupper($this->transmission);
    }

    /**
     * Add image
     */
    public function addImages(UserCarImage $image)
    {
        $this->images[] = $image->setCar($this);

        return $this;
    }

    /**
     * Remove image
     */
    public function removeImages(UserCarImage $image)
    {
        $this->images->removeElement($image);
    }

    public function setImages($images)
    {
        foreach ($images as $image) {
            $image->setCar($this);
        }
        $this->images = $images;

        return $this;
    }

    /**
     * Get images
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getImages()
    {
        return $this->images;
    }
}
