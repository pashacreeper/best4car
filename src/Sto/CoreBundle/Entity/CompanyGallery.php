<?php

namespace Sto\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * CompanyGallery
 *
 * @ORM\Table(name="company_gallery")
 * @ORM\Entity(repositoryClass="Sto\CoreBundle\Repository\CompanyGalleryRepository")
 * @Vich\Uploadable
 */
class CompanyGallery
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @Assert\File(
     *     maxSize="5M",
     *     mimeTypes={"image/png", "image/jpeg", "image/pjpeg"}
     * )
     * @Vich\UploadableField(mapping="company_gallery", fileNameProperty="imageName")
     * @var File $image
     */
    protected $image;

    /**
     * @ORM\Column(name="image", type="string", length=255, nullable=true)
     */
    protected $imageName;

    /**
     * @ORM\Column(name="visible", type="boolean", options={"default" = 1}, nullable=true)
     */
    private $visible = 1;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\Column(name="company_id", type="integer", nullable=true)
     */
    private $companyId;

    /**
     * @ORM\ManyToOne(targetEntity="Company", inversedBy="gallery")
     * @ORM\JoinColumn(name="company_id", referencedColumnName="id")
     */
    private $company;

    public function __construct(Company $company = null)
    {
        if ($company) {
            $this->setCompany($company);
        }

        $this->visible = false;
    }

    public function __toString()
    {
        return (string) $this->getName();
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
     * Set image
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     */
    public function getImage()
    {
        return $this->image;
    }

    public function getImageName()
    {
        return $this->imageName;
    }

    /**
     * Set visible
     */
    public function setVisible($visible)
    {
        $this->visible = $visible;

        return $this;
    }

    /**
     * Get visible
     */
    public function isVisible()
    {
        return $this->visible;
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

    public function getCompany()
    {
        return $this->company;
    }

    public function setCompany(Company $company)
    {
        $this->company = $company;
        $this->companyId = $company->getId();

        return $this;
    }

    public function getCompanyId()
    {
        return $this->companyId;
    }

    public function setCompanyId($companyId)
    {
        $this->companyId = $companyId;

        return $this;
    }

    public function getImagePath()
    {
        return $this->imageName == null ? "/bundles/stocore/images/notimage.png" : "/storage/images/company/company_gallery/{$this->imageName}";
    }

    /**
     * Set imageName
     *
     * @param  string         $imageName
     * @return CompanyGallery
     */
    public function setImageName($imageName)
    {
        $this->imageName = $imageName;

        return $this;
    }

    /**
     * Get visible
     *
     * @return boolean
     */
    public function getVisible()
    {
        return $this->visible;
    }

}
