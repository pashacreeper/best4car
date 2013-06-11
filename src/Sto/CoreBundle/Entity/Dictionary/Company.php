<?php

namespace Sto\CoreBundle\Entity\Dictionary;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile,
    Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Company
 *
 * @ORM\Entity()
 * @Vich\Uploadable
 */
class Company extends Base
{
    /**
     * @Assert\Image(
     *     maxSize="256k",
     *     mimeTypes={"image/png", "image/jpeg"},
     *     maxWidth="256",
     *     maxHeight="256"
     * )
     * @Vich\UploadableField(mapping="company_type_icon", fileNameProperty="iconNameMap")
     */
    protected $iconMap;

    /**
     * @ORM\Column(type="string", length=255, name="icon_name_map", nullable=true)
     */
    protected $iconNameMap;

    /**
     * @Assert\Image(
     *     maxSize="256k",
     *     mimeTypes={"image/png", "image/jpeg"},
     *     maxWidth="512",
     *     maxHeight="512"
     * )
     * @Vich\UploadableField(mapping="company_type_icon", fileNameProperty="iconNameSmall")
     */
    protected $iconSmall;

    /**
     * @ORM\Column(type="string", length=255, name="icon_name_small", nullable=true)
     */
    protected $iconNameSmall;

    /**
     * @Assert\Image(
     *     maxSize="512k",
     *     mimeTypes={"image/png", "image/jpeg"},
     *     maxWidth="1024",
     *     maxHeight="1024"
     * )
     * @Vich\UploadableField(mapping="company_type_icon", fileNameProperty="iconNameLarge")
     */
    protected $iconLarge;

    /**
     * @ORM\Column(type="string", length=255, name="icon_name_large", nullable=true)
     */
    protected $iconNameLarge;

    /**
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\ManyToMany(targetEntity="AutoServices")
     */
    private $autoServices;

    public function __construct()
    {
        parent::__construct();
        $this->autoServices = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set iconMap
     *
     * @param  string  $iconMap
     * @return Country
     */
    public function setIconMap($iconMap)
    {
        $this->iconMap = $iconMap;
        if ($iconMap instanceof UploadedFile) {
            $this->setUpdatedAt(new \DateTime());
        }

        return $this;
    }

    /**
     * Get iconMap
     *
     * @return string
     */
    public function getIconMap()
    {
        return $this->iconMap;
    }

    public function setIconNameMap($icon)
    {
        $this->iconNameMap = $icon;

        return $this;
    }

    public function getIconNameMap()
    {
        return $this->iconNameMap;
    }

    /**
     * Set iconSmall
     *
     * @param  string  $iconSmall
     * @return Country
     */
    public function setIconSmall($iconSmall)
    {
        $this->iconSmall = $iconSmall;
        if ($iconSmall instanceof UploadedFile) {
            $this->setUpdatedAt(new \DateTime());
        }

        return $this;
    }

    /**
     * Get iconSmall
     *
     * @return string
     */
    public function getIconSmall()
    {
        return $this->iconSmall;
    }

    public function setIconNameSnall($icon)
    {
        $this->iconNameSmall = $icon;

        return $this;
    }

    public function getIconNameSmall()
    {
        return $this->iconNameSmall;
    }

    /**
     * Set iconLarge
     *
     * @param  string  $iconLarge
     * @return Country
     */
    public function setIconLarge($iconLarge)
    {
        $this->iconLarge = $iconLarge;
        if ($iconLarge instanceof UploadedFile) {
            $this->setUpdatedAt(new \DateTime());
        }

        return $this;
    }

    /**
     * Get iconLarge
     *
     * @return string
     */
    public function getIconLarge()
    {
        return $this->iconLarge;
    }

    public function setIconNameLarge($icon)
    {
        $this->iconNameLarge = $icon;

        return $this;
    }

    public function getIconNameLarge()
    {
        return $this->iconNameLarge;
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

    public function setAutoServices($value)
    {
        $this->autoServices = $value;

        return $this;
    }

    public function getAutoServices()
    {
        return $this->autoServices;
    }
}
