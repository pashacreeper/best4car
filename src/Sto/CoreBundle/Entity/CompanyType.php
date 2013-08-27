<?php

namespace Sto\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM,
    Symfony\Component\HttpFoundation\File\UploadedFile,
    Symfony\Component\Validator\Constraints as Assert,
    Vich\UploaderBundle\Mapping\Annotation as Vich,
    Sto\CoreBundle\Entity\AutoServices;

/**
 * Company
 *
 * @ORM\Entity()
 * @ORM\Table(name="company_types")
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
     * @param  string $code
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
     * @param  string $name
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
     * @param  integer $parentId
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
     * @param  string  $iconMap
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
     * Set iconSmall
     *
     * @param  string  $iconSmall
     * @return CompanyType
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
     * @return CompanyType
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
