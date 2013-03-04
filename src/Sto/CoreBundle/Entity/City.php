<?php

namespace Sto\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File,
    Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * City
 *
 * @ORM\Table(name="cities")
 * @ORM\Entity(repositoryClass="Sto\CoreBundle\Repository\CityRepository")
 * @Vich\Uploadable
 */
class City
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
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=255)
     */
    private $code;

    /**
     * @var File $icon
     *
     * @Assert\File(
     *     maxSize="2M",
     *     mimeTypes={"image/png", "image/jpeg", "image/pjpeg"}
     * )
     * @Vich\UploadableField(mapping="city_icon", fileNameProperty="iconName")
     */
    protected $icon;

    /**
     * @var string $iconName
     *
     * @ORM\Column(type="string", length=255, name="icon_name", nullable=true)
     */
    protected $iconName;

    /**
     * @var File $icon
     *
     * @Assert\File(
     *     maxSize="2M",
     *     mimeTypes={"image/png", "image/jpeg", "image/pjpeg"}
     * )
     * @Vich\UploadableField(mapping="city_image", fileNameProperty="imageName")
     */
    protected $image;

    /**
     * @var string
     *
     * @ORM\Column(name="image_name", type="string", length=255, nullable=true)
     */
    private $imageName;

    /**
     * @var integer
     *
     * @ORM\Column(name="country_id", type="integer")
     */
    private $countryId;

    /**
     * @ORM\ManyToOne(targetEntity="Country", inversedBy="countries")
     * @ORM\JoinColumn(name="country_id", referencedColumnName="id")
     */
    private $country;

    /**
     * Constructor
     */
    public function __construct(Country $country = null)
    {
        if ($country != null) {
            $this->country = $country;
            $country->addCity($this);
        }
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
     * @param  string $name
     * @return City
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
     * Set code
     *
     * @param  string $code
     * @return City
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set icon
     *
     * @param  string  $icon
     * @return Country
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * Get icon
     *
     * @return string
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * Set image
     *
     * @param  string  $image
     * @return Country
     */
    public function setImage($image)
    {
        $this->image = $image;

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

    /**
     * Set country_id
     *
     * @param  integer $countryId
     * @return City
     */
    public function setCountryId($countryId)
    {
        $this->countryId = $countryId;

        return $this;
    }

    /**
     * Get country_id
     *
     * @return integer
     */
    public function getCountryId()
    {
        return $this->countryId;
    }

    /**
     * Set country
     *
     */
    public function setCountry(Country $country = null)
    {
        $this->country = $country;
        if ($country != null) {
            $this->countryId = $country->getId();
        }

        return $this;
    }

    /**
     * Get country
     *
     */
    public function getCountry()
    {
        return $this->country;
    }

    public function __toString()
    {
        return $this->name;
    }

    /**
     * @param string $icon
     */
    public function setIconName($icon)
    {
        $this->iconName = $icon;

        return $this;
    }

    /**
     * @return string
     */
    public function getIconName()
    {
        return $this->iconName;
    }

    /**
     * @param string $image
     */
    public function setImageName($image)
    {
        $this->imageName = $image;

        return $this;
    }

    /**
     * @return string
     */
    public function getImageName()
    {
        return $this->imageName;
    }
}
