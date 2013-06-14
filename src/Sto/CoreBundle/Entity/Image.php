<?php
namespace Sto\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @Vich\Uploadable
 */
Class Image {

    /**
     * @Assert\File(
     *     maxSize="2M",
     *     mimeTypes={"image/png", "image/jpeg", "image/pjpeg"}
     * )
     * @Vich\UploadableField(mapping="deal_image", fileNameProperty="imageName")
     */
    protected $image;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $imageName;

    public function preUpload()
    {
        print "YYYY"; exit;
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
}
