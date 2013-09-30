<?php

namespace Sto\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as Serializer;

/**
 * Country
 *
 * @ORM\Entity()
 * @ORM\Table(name="countries")
 * @Vich\Uploadable
 */
class Country
{
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
     * @ORM\OneToMany(targetEntity="Country", mappedBy="parent", cascade={"remove"})
     * @Serializer\Exclude
     */
    private $children;

    /**
     * @ORM\ManyToOne(targetEntity="Country", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     * @Serializer\Exclude
     */
    private $parent;

    /**
     * @ORM\Column(name="parent_id", type="integer", nullable=true)
     */
    private $parentId;

    /**
     * @ORM\Column(name="position", type="integer", nullable=true)
     * @Serializer\Exclude
     */
    private $position;

    /**
     * @Assert\Image(
     *     maxSize="512k",
     *     mimeTypes={"image/png", "image/jpeg"},
     *     maxWidth="1024",
     *     maxHeight="1024"
     * )
     * @Vich\UploadableField(mapping="country_icon", fileNameProperty="iconName")
     * @Serializer\Exclude
     */
    protected $icon;

    /**
     * @ORM\Column(type="string", length=255, name="icon_name", nullable=true)
     */
    protected $iconName;

    /**
     * @Assert\Image(
     *     maxSize="5M",
     *     mimeTypes={"image/png", "image/jpeg"},
     *     maxWidth="2048",
     *     maxHeight="2048"
     * )
     * @Vich\UploadableField(mapping="country_image", fileNameProperty="imageName")
     * @Serializer\Exclude
     */
    protected $image;

    /**
     * @ORM\Column(name="image_name", type="string", length=255, nullable=true)
     */
    private $imageName;

    /**
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     * @Serializer\Exclude
     */
    private $updatedAt;

    /**
     * @ORM\Column(name="gps", type="string", nullable=true)
     */
    private $gps;

    /**
     * @ORM\OneToMany(targetEntity="\Sto\CoreBundle\Entity\Company", mappedBy="city")
     * @Serializer\Exclude
     */
    private $companies;

    /**
     * @ORM\OneToMany(targetEntity="\Sto\UserBundle\Entity\User", mappedBy="city")
     * @Serializer\Exclude
     */
    private $users;

    public function __construct()
    {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
        $this->companies = new ArrayCollection();
        $this->users = new ArrayCollection();
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
     * @param  string  $code
     * @return Country
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
     * @param  string  $name
     * @return Country
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
     * @return Country
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
    public function setParent(Country $parent = null)
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
    public function addChildren(Country $children)
    {
        $this->children[] = $children;

        return $this;
    }

    /**
     * Remove children
     */
    public function removeChildren(Country $children)
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
     * Set image
     *
     * @param  string  $image
     * @return Country
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

    public function setUpdatedAt($date)
    {
        $this->updatedAt = $date;

        return $this;
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function addCompany(\Sto\CoreBundle\Entity\Company $company)
    {
        $this->companies[] = $company;

        return $this;
    }

    public function removeCompany(\Sto\CoreBundle\Entity\Company $company)
    {
        $this->companies->removeElement($company);

        return $this;
    }

    public function getCompanies()
    {
        return $this->companies->toArray();
    }

    public function addUser(\Sto\UserBundle\Entity\User $user)
    {
        $this->users[] = $user;

        return $this;
    }

    public function removeUser(\Sto\UserBundle\Entity\User $user)
    {
        $this->users->removeElement($user);

        return $this;
    }

    public function getUsers()
    {
        return $this->users->toArray();
    }

    public function setGps($gps)
    {
        $this->gps = $gps;

        return $this;
    }

    public function getGps()
    {
        return $this->gps;
    }

    public function preRemove()
    {
    }

    /**
     * Add children
     *
     * @param  \Sto\CoreBundle\Entity\Country $children
     * @return Country
     */
    public function addChild(\Sto\CoreBundle\Entity\Country $children)
    {
        $this->children[] = $children;

        return $this;
    }

    /**
     * Remove children
     *
     * @param \Sto\CoreBundle\Entity\Country $children
     */
    public function removeChild(\Sto\CoreBundle\Entity\Country $children)
    {
        $this->children->removeElement($children);
    }
}
