<?php

namespace Sto\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Sto\CoreBundle\Entity\AutoServices;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Company
 *
 * @ORM\Entity()
 * @ORM\Table(name="company_types", indexes={@ORM\Index(name="company_types_search_idx", columns={"name"})})
 * @Vich\Uploadable
 */
class CompanyType
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
     * @Vich\UploadableField(mapping="company_type_icon", fileNameProperty="iconNameMapSelected")
     */
    protected $iconMapSelected;

    /**
     * @ORM\Column(type="string", length=255, name="icon_name_map_selected", nullable=true)
     */
    protected $iconNameMapSelected;

    /**
     * @Assert\Image(
     *     maxSize="256k",
     *     mimeTypes={"image/png", "image/jpeg"},
     *     maxWidth="512",
     *     maxHeight="512"
     * )
     * @Vich\UploadableField(mapping="company_type_icon", fileNameProperty="iconNameMapVip")
     */
    protected $iconMapVip;

    /**
     * @ORM\Column(type="string", length=255, name="icon_name_map_vip", nullable=true)
     */
    protected $iconNameMapVip;

    /**
     * @Assert\Image(
     *     maxSize="256k",
     *     mimeTypes={"image/png", "image/jpeg"},
     *     maxWidth="512",
     *     maxHeight="512"
     * )
     * @Vich\UploadableField(mapping="company_type_icon", fileNameProperty="iconNameMapVipSelected")
     */
    protected $iconMapVipSelected;

    /**
     * @ORM\Column(type="string", length=255, name="icon_name_map_vip_selected", nullable=true)
     */
    protected $iconNameMapVipSelected;

    /**
     * @Assert\Image(
     *     maxSize="512k",
     *     mimeTypes={"image/png", "image/jpeg"},
     *     maxWidth="1024",
     *     maxHeight="1024"
     * )
     * @Vich\UploadableField(mapping="company_type_icon", fileNameProperty="iconNameCompanyCard")
     */
    protected $iconCompanyCard;

    /**
     * @ORM\Column(type="string", length=255, name="icon_name_company_card", nullable=true)
     */
    protected $iconNameCompanyCard;

    /**
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\ManyToMany(targetEntity="Sto\CoreBundle\Entity\AutoServices")
     */
    private $autoServices;

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="short_name", type="string", length=15, nullable=true)
     */
    private $shortName;

    /**
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="CompanyType", mappedBy="parent", cascade={"remove"})
     */
    private $children;

    /**
     * @ORM\ManyToOne(targetEntity="CompanyType", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    private $parent;

    /**
     * @ORM\Column(name="parent_id", type="integer", nullable=true)
     */
    private $parentId;

    /**
     * @ORM\Column(name="position", type="integer", nullable=true)
     */
    private $position;

    public function __construct()
    {
        $this->autoServices = new \Doctrine\Common\Collections\ArrayCollection();
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set code
     *
     * @param  string      $code
     * @return CompanyType
     */
    public function setShortName($shortName)
    {
        $this->shortName = $shortName;

        return $this;
    }

    /**
     * Get name
     */
    public function getShortName()
    {
        return $this->shortName;
    }

    /**
     * Set name
     *
     * @param  string      $name
     * @return CompanyType
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set parentId
     *
     * @param  integer     $parentId
     * @return CompanyType
     */
    public function setParentId($parentId)
    {
        $this->parentId = $parentId;

        return $this;
    }

    /**
     * Get parentId
     */
    public function getParentId()
    {
        return $this->parentId;
    }

    /**
     * Set parent
     */
    public function setParent(CompanyType $parent = null)
    {
        $this->parent = $parent;
        if ($parent != null) {
            $this->parentId = $parent->getId();
        }

        return $this;
    }

    /**
     * Get parent
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add children
     */
    public function addChildren(CompanyType $children)
    {
        $this->children[] = $children;

        return $this;
    }

    /**
     * Remove children
     */
    public function removeChildren(CompanyType $children)
    {
        $this->children->removeElement($children);

        return $this;
    }

    /**
     * Get children
     */
    public function getChildren()
    {
        return $this->children;
    }

    public function __toString()
    {
        return $this->name;
    }

    /**
     * set Position
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * get Position
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set iconMap
     *
     * @param  string      $iconMap
     * @return CompanyType
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
     * Set iconMapSelected
     *
     * @param  string      $iconMapSelected
     * @return CompanyType
     */
    public function setIconMapSelected($iconMapSelected)
    {
        $this->iconMapSelected = $iconMapSelected;
        if ($iconMapSelected instanceof UploadedFile) {
            $this->setUpdatedAt(new \DateTime());
        }

        return $this;
    }

    /**
     * Get iconMapSelected
     *
     * @return string
     */
    public function getIconMapSelected()
    {
        return $this->iconMapSelected;
    }

    /**
     * Set iconCompanyCard
     *
     * @param  string      $iconCompanyCard
     * @return CompanyType
     */
    public function setIconCompanyCard($iconCompanyCard)
    {
        $this->iconCompanyCard = $iconCompanyCard;
        if ($iconCompanyCard instanceof UploadedFile) {
            $this->setUpdatedAt(new \DateTime());
        }

        return $this;
    }

    /**
     * Get iconCompanyCard
     *
     * @return string
     */
    public function getIconCompanyCard()
    {
        return $this->iconCompanyCard;
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

    /**
     * Set iconNameMapSelected
     *
     * @param  string      $iconNameMapSelected
     * @return CompanyType
     */
    public function setIconNameMapSelected($iconNameMapSelected)
    {
        $this->iconNameMapSelected = $iconNameMapSelected;

        return $this;
    }

    /**
     * Get iconNameMapSelected
     *
     * @return string
     */
    public function getIconNameMapSelected()
    {
        return $this->iconNameMapSelected;
    }

    /**
     * Set iconNameCompanyCard
     *
     * @param  string      $iconNameCompanyCard
     * @return CompanyType
     */
    public function setIconNameCompanyCard($iconNameCompanyCard)
    {
        $this->iconNameCompanyCard = $iconNameCompanyCard;

        return $this;
    }

    /**
     * Get iconNameCompanyCard
     *
     * @return string
     */
    public function getIconNameCompanyCard()
    {
        return $this->iconNameCompanyCard;
    }

    /**
     * Add autoServices
     *
     * @param  \Sto\CoreBundle\Entity\AutoServices $autoServices
     * @return CompanyType
     */
    public function addAutoService(\Sto\CoreBundle\Entity\AutoServices $autoServices)
    {
        $this->autoServices[] = $autoServices;

        return $this;
    }

    /**
     * Remove autoServices
     *
     * @param \Sto\CoreBundle\Entity\AutoServices $autoServices
     */
    public function removeAutoService(\Sto\CoreBundle\Entity\AutoServices $autoServices)
    {
        $this->autoServices->removeElement($autoServices);
    }

    /**
     * Add children
     *
     * @param  \Sto\CoreBundle\Entity\CompanyType $children
     * @return CompanyType
     */
    public function addChild(\Sto\CoreBundle\Entity\CompanyType $children)
    {
        $this->children[] = $children;

        return $this;
    }

    /**
     * Remove children
     *
     * @param \Sto\CoreBundle\Entity\CompanyType $children
     */
    public function removeChild(\Sto\CoreBundle\Entity\CompanyType $children)
    {
        $this->children->removeElement($children);
    }

    /**
     * Set iconMapVip
     *
     * @param UploadedFile $iconMapVip
     */
    public function setIconMapVip(UploadedFile $iconMapVip = null)
    {
        $this->iconMapVip = $iconMapVip;
        if ($iconMapVip instanceof UploadedFile) {
            $this->setUpdatedAt(new \DateTime());
        }

        return $this;
    }

    /**
     * Get iconMapVip
     *
     * @return UploadedFile
     */
    public function getIconMapVip()
    {
        return $this->iconMapVip;
    }

    /**
     * Set iconMapVipSelected
     *
     * @param UploadedFile $iconMapVipSelected
     */
    public function setIconMapVipSelected(UploadedFile $iconMapVipSelected = null)
    {
        $this->iconMapVipSelected = $iconMapVipSelected;
        if ($iconMapVipSelected instanceof UploadedFile) {
            $this->setUpdatedAt(new \DateTime());
        }

        return $this;
    }

    /**
     * Get iconMapVipSelected
     *
     * @return UploadedFile
     */
    public function getIconMapVipSelected()
    {
        return $this->iconMapVipSelected;
    }

    /**
     * Set iconNameMapVip
     *
     * @param  string      $iconNameMapVip
     * @return CompanyType
     */
    public function setIconNameMapVip($iconNameMapVip)
    {
        $this->iconNameMapVip = $iconNameMapVip;

        return $this;
    }

    /**
     * Get iconNameMapVip
     *
     * @return string
     */
    public function getIconNameMapVip()
    {
        return $this->iconNameMapVip;
    }

    /**
     * Set iconNameMapVipSelected
     *
     * @param  string      $iconNameMapVipSelected
     * @return CompanyType
     */
    public function setIconNameMapVipSelected($iconNameMapVipSelected)
    {
        $this->iconNameMapVipSelected = $iconNameMapVipSelected;

        return $this;
    }

    /**
     * Get iconNameMapVipSelected
     *
     * @return string
     */
    public function getIconNameMapVipSelected()
    {
        return $this->iconNameMapVipSelected;
    }
}
