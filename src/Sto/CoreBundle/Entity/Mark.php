<?php

namespace Sto\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Mark
 *
 * @ORM\Entity()
 * @ORM\Table(name="catalog_marks", indexes={@ORM\Index(name="marks_search_idx", columns={"name"})})
 * @Vich\Uploadable
 */
class Mark
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(name="name", type="string", length=255)
     */
    protected $name;

    /**
     * @ORM\OneToMany(targetEntity="Model", mappedBy="parent")
     */
    protected $children;

    /**
     * @Assert\Url()
     * @ORM\Column(name="uri", type="string", length=255, nullable=true)
     */
    protected $uri;

    /**
     * @var boolean
     * @ORM\Column(name="visible", type="boolean")
     */
    protected $visible;

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
     * Constructor
     */
    public function __construct()
    {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
        $this->visible = true;

        return $this;
    }

    /**
     * Get id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param  string $name
     * @return Mark
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
     * Add children
     */
    public function addChildren(Model $children)
    {
        $this->children[] = $children;

        return $this;
    }

    /**
     * Remove children
     */
    public function removeChildren(Model $children)
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

    /**
     * Set uri
     *
     * @param string $uri
     */
    public function setUri($uri)
    {
        $this->uri = $uri;

        return $this;
    }

    /**
     * Get uri
     */
    public function getUri()
    {
        return $this->uri;
    }

    public function isVisible()
    {
        return $this->visible;
    }

    public function setVisible($visible)
    {
        $this->visible = $visible;

        return $this;
    }

    public function __toString()
    {
        return $this->name;
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

    public function getImagePath()
    {
        return $this->iconName == null ? "/bundles/stocore/images/notimage.png" : "/storage/images/catalog/icons/{$this->iconName}";
    }
}
