<?php

namespace Sto\CoreBundle\Entity\Catalog;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Mark
 *
 * @ORM\Entity()
 * @Vich\Uploadable
 */
class Mark extends Base
{
    /**
     * @Assert\Image(
     *     maxSize="512k",
     *     mimeTypes={"image/png", "image/jpeg"},
     *     maxWidth="1024",
     *     maxHeight="1024"
     * )
     * @Vich\UploadableField(mapping="catalog_icons", fileNameProperty="iconName")
     */
    private $icon;

    /**
     * @ORM\Column(type="string", length=255, name="icon_name", nullable=true)
     */
    private $iconName;

    /**
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * Set icon
     *
     * @param  string  $icon
     * @return Country
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;
        if ($icon instanceof UploadedFile) {
            $this->setUpdatedAt(new \DateTime());
        }

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
