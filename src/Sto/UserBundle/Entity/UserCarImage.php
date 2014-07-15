<?php

namespace Sto\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sto\UserBundle\Entity\UserCar;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User Car Images
 *
 * @ORM\Entity
 * @ORM\Table(name="user_car_images")
 * @Vich\Uploadable
 */
class UserCarImage
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
     * @ORM\ManyToOne(targetEntity="UserCar", inversedBy="images")
     * @ORM\JoinColumn(name="car_id", referencedColumnName="id")
     */
    private $car;

    /**
     * @Assert\File(
     *     maxSize="10M",
     *     mimeTypes={"image/png", "image/jpeg", "image/pjpeg"}
     * )
     * @Vich\UploadableField(mapping="user_car_image", fileNameProperty="imageName")
     */
    protected $image;

    /**
     * @ORM\Column(name="image", type="string", length=255, nullable=true)
     */
    protected $imageName;

    /**
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

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
     * Set car
     *
     * @param UserCar $car
     *
     * @internal param \Sto\UserBundle\Entity\UserCar $user
     * @return UserCarImage
     */
    public function setCar(UserCar $car)
    {
        $this->car = $car;

        return $this;
    }

    /**
     * Get car
     *
     * @return UserCar
     */
    public function getCar()
    {
        return $this->car;
    }

    /**
     * Set image
     *
     * @param $image
     *
     * @return $this
     */
    public function setImage($image)
    {
        $this->image = $image;
        if ($image instanceof UploadedFile) {
            $this->setUpdatedAt(new \DateTime());
        }

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    public function getImageName()
    {
        return $this->imageName;
    }

    public function setUpdatedAt($date)
    {
        $this->updatedAt = $date;

        return $this;
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function getImagePath()
    {
        return $this->imageName == null ? "/bundles/stocore/images/notimage.png" : "/storage/images/car/gallery/{$this->imageName}";
    }

    /**
     * @param mixed $imageName
     */
    public function setImageName($imageName)
    {
        $this->imageName = $imageName;
    }
}
