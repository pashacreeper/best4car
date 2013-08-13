<?php

namespace Sto\CoreBundle\Entity\Dictionary;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * AdditionalService
 *
 * @ORM\Entity()
 */
class AdditionalService extends Base
{
    /**
     * @ORM\ManyToMany(targetEntity="\Sto\CoreBundle\Entity\Company")
     */
    private $companies;

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
     * Constructor
     */
    public function __construct()
    {
        $this->companies = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set iconNameMap
     *
     * @param  string            $iconNameMap
     * @return AdditionalService
     */
    public function setIconNameMap($iconNameMap)
    {
        $this->iconNameMap = $iconNameMap;

        return $this;
    }

    /**
     * Get iconNameMap
     *
     * @return string
     */
    public function getIconNameMap()
    {
        return $this->iconNameMap;
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

    /**
     * Set iconNameSmall
     *
     * @param  string            $iconNameSmall
     * @return AdditionalService
     */
    public function setIconNameSmall($iconNameSmall)
    {
        $this->iconNameSmall = $iconNameSmall;

        return $this;
    }

    /**
     * Get iconNameSmall
     *
     * @return string
     */
    public function getIconNameSmall()
    {
        return $this->iconNameSmall;
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
     * Set iconNameLarge
     *
     * @param  string            $iconNameLarge
     * @return AdditionalService
     */
    public function setIconNameLarge($iconNameLarge)
    {
        $this->iconNameLarge = $iconNameLarge;

        return $this;
    }

    /**
     * Get iconNameLarge
     *
     * @return string
     */
    public function getIconNameLarge()
    {
        return $this->iconNameLarge;
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

    /**
     * Add companies
     *
     * @param  \Sto\CoreBundle\Entity\Company $companies
     * @return AdditionalService
     */
    public function addCompanie(\Sto\CoreBundle\Entity\Company $companies)
    {
        $this->companies[] = $companies;

        return $this;
    }

    /**
     * Remove companies
     *
     * @param \Sto\CoreBundle\Entity\Company $companies
     */
    public function removeCompanie(\Sto\CoreBundle\Entity\Company $companies)
    {
        $this->companies->removeElement($companies);
    }

    /**
     * Get companies
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCompanies()
    {
        return $this->companies;
    }
}
