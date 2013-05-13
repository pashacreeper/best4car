<?php

namespace Sto\CoreBundle\Entity\Dictionary;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Country
 *
 * @ORM\Entity()
 * @Vich\Uploadable
 */
class Country extends Base
{
    /**
     * @Assert\Image(
     *     maxSize="512k",
     *     mimeTypes={"image/png", "image/jpeg"},
     *     maxWidth="1024",
     *     maxHeight="1024"
     * )
     * @Vich\UploadableField(mapping="country_icon", fileNameProperty="iconName")
     */
    protected $icon;

    /**
     * @ORM\Column(type="string", length=255, name="icon_name", nullable=true)
     */
    protected $iconName;

    /**
     * @Assert\Image(
     *     maxSize="2048k",
     *     mimeTypes={"image/png", "image/jpeg"},
     *     maxWidth="2048",
     *     maxHeight="2048"
     * )
     * @Vich\UploadableField(mapping="country_image", fileNameProperty="imageName")
     */
    protected $image;

    /**
     * @ORM\Column(name="image_name", type="string", length=255, nullable=true)
     */
    private $imageName;

    /**
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\Column(name="gps", type="string", nullable=true)
     */
    private $gps;

    /**
     * @ORM\OneToMany(targetEntity="\Sto\CoreBundle\Entity\Company", mappedBy="city")
     */
    private $companies;

    /**
     * @ORM\OneToMany(targetEntity="\Sto\UserBundle\Entity\User", mappedBy="city")
     */
    private $users;

    public function __construct()
    {
        parent::__construct();
        $this->companies = new ArrayCollection();
        $this->users = new ArrayCollection();
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
}
