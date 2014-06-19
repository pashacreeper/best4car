<?php

namespace Sto\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Sto\ContentBundle\Form\Extension\ChoiceList\BodyType;
use Sto\ContentBundle\Form\Extension\ChoiceList\EngineType;
use Sto\ContentBundle\Form\Extension\ChoiceList\TransmissionType;
use Sto\ContentBundle\Form\Extension\ChoiceList\WheelType;
use Sto\CoreBundle\Entity\CustomModification;
use Sto\CoreBundle\Entity\Mark;
use Sto\CoreBundle\Entity\Model;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * User Cars
 *
 * @ORM\Entity
 * @ORM\Table(name="user_cars")
 * @ORM\HasLifecycleCallbacks()
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
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updatedt_at", type="datetime")
     */
    private $updatedtAt;

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
     * @ORM\OneToOne(targetEntity="Sto\CoreBundle\Entity\CustomModification", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\JoinColumn(name="custom_modification_id", referencedColumnName="id")
     */
    private $customModification;

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
     * @Assert\Url()
     * @ORM\Column(name="drive2", type="string", length=255, nullable=true)
     */
    protected $drive2;

    /**
     * @ORM\OneToMany(targetEntity="UserCarImage", mappedBy="car", cascade={"all"})
     */
    private $images;

    /**
     * @var int
     *
     * @ORM\Column(name="year", type="integer")
     */
    protected $year;

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
     * @param  User        $user
     * @return RatingGroup
     */
    public function setUser(User $user)
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
     * @param  Mark    $mark
     * @return UserCar
     */
    public function setMark(Mark $mark)
    {
        $this->mark = $mark;

        return $this;
    }

    /**
     * Get mark
     *
     * @return Mark
     */
    public function getMark()
    {
        return $this->mark;
    }

    /**
     * Set model
     *
     * @param  Model   $model
     * @return UserCar
     */
    public function setModel(Model $model)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Get model
     *
     * @return Model
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Set modification
     *
     * @param  \Sto\CoreBundle\Entity\Modification|null $modification
     * @return UserCar
     */
    public function setModification($modification)
    {
        $this->modification = $modification;

        return $this;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function resetCustomModification()
    {
        if ($this->modification) {
            $this->setCustomModification(null);
        }
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
     * @param  string  $transmission
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
     * Set year
     *
     * @param  string  $year
     * @return UserCar
     */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Get year
     *
     * @return string
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set vin
     *
     * @param  string  $vin
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
     * @param  string  $drive2
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
        if (!$this->transmission) {
            return "";
        }

        return strtoupper($this->transmission);
    }

    public function getTransmissionHuman()
    {
        if (!$this->transmission) {
            return null;
        }

        return TransmissionType::getHumanTypes()[$this->transmission];
    }

    /**
     * Add image
     */
    public function addImages(UserCarImage $image)
    {
        $image->setCar($this);
        $this->images[] = $image;

        return $this;
    }

    /**
     * Remove image
     */
    public function removeImages(UserCarImage $image)
    {
        $this->images->removeElement($image);
    }

    protected function nonEmptyImages($images)
    {
        $result = new ArrayCollection;
        foreach ($images as $image) {
            if ($image->getImage() || $image->getImageName()) {
                $result[] = $image;
            }
        }

        return $result;
    }

    public function setImages($images)
    {
        $images = $this->nonEmptyImages($images);

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

    public function isCustomModification()
    {
        return $this->getId() && !$this->getModification() && $this->getCustomModification();
    }

    public function getDescription()
    {
        $desc = $this->getYear(). " г.в., ";
        if ($this->modification) {
            $desc .= $this->modification->getName();
        } elseif ($this->getCustomModification()) {
            $modification = $this->getCustomModification();
            $desc .= $modification->getEngineModel();
            if ($modification->getEnginePower()) {
                $desc .= " ({$modification->getEnginePower()})";
            }
        }
        $desc .= " ".$this->getTransmissionName();

        return $desc;
    }

    public function getBodyTypeWrapper()
    {
        if ($this->modification) {
            return $this->modification->getBodyType();
        } else {
            return $this->getBodyTypeName();
        }
    }

    public function getBodyTypeName()
    {
        if ($this->isCustomModification()) {
            return BodyType::getOptions()[$this->getCustomModification()->getBodyType()];
        }

        return null;
    }

    public function getWheelTypeName()
    {
        if ($this->isCustomModification()) {
            return WheelType::getOptions()[$this->getCustomModification()->getWheelType()];
        }

        return null;
    }

    public function getEngineTypeName()
    {
        if ($this->isCustomModification()) {
            return EngineType::getOptions()[$this->getCustomModification()->getEngineType()];
        }

        return null;
    }

    public function getEngineDescription()
    {
        $parts = [];
        if ($this->modification) {
            $parts[] = $this->modification->getEngine(). " куб.см.";
            $parts[] = $this->modification->getPower(). " л.с.";
        } elseif ($modification = $this->getCustomModification()) {
            if ($modification->getEngineType()) {
                $parts[] = $this->getEngineTypeName();
            }
            if ($modification->getEngineModel()) {
                $parts[] = $modification->getEngineModel();
            }
            if ($modification->getEngineVolume()) {
                $parts[] = $modification->getEngineVolume(). " куб.см.";
            }
            if ($modification->getEnginePower()) {
                $parts[] = $modification->getEnginePower(). " л.с.";
            }
            foreach ($modification->getFuelTypes() as $type) {
                $parts[] = "АИ-$type";
            }
        }

        return implode(", ", $parts);
    }

    /**
     * Set customModification
     *
     * @param  CustomModification $customModification
     * @return UserCar
     */
    public function setCustomModification(CustomModification $customModification = null)
    {
        $this->customModification = $customModification;

        return $this;
    }

    /**
     * Get customModification
     *
     * @return CustomModification
     */
    public function getCustomModification()
    {
        return $this->customModification;
    }

    /**
     * Add images
     *
     * @param  UserCarImage $images
     * @return UserCar
     */
    public function addImage(UserCarImage $images)
    {
        $this->images[] = $images;

        return $this;
    }

    /**
     * Remove images
     *
     * @param UserCarImage $images
     */
    public function removeImage(UserCarImage $images)
    {
        $this->images->removeElement($images);
    }

    /**
     * Set createdAt
     *
     * @param  \DateTime $createdAt
     * @return UserCar
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedtAt
     *
     * @param  \DateTime $updatedtAt
     * @return UserCar
     */
    public function setUpdatedtAt($updatedtAt)
    {
        $this->updatedtAt = $updatedtAt;

        return $this;
    }

    /**
     * Get updatedtAt
     *
     * @return \DateTime
     */
    public function getUpdatedtAt()
    {
        return $this->updatedtAt;
    }
}
