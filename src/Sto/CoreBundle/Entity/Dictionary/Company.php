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
     * @ORM\ManyToMany(targetEntity="Company")
     */
    private $companies;

    /**
     * @var File $iconMap
     *
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
     * @var string $iconNameMap
     *
     * @ORM\Column(type="string", length=255, name="icon_name_map", nullable=true)
     */
    protected $iconNameMap;

    /**
     * @var File $iconSmall
     *
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
     * @var string $iconNameSmall
     *
     * @ORM\Column(type="string", length=255, name="icon_name_small", nullable=true)
     */
    protected $iconNameSmall;

    /**
     * @var File $iconLarge
     *
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
     * @var string $iconNameLarge
     *
     * @ORM\Column(type="string", length=255, name="icon_name_large", nullable=true)
     */
    protected $iconNameLarge;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

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

    public function setUpdatedAt($date)
    {
        $this->updatedAt = $date;

        return $this;
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
}
