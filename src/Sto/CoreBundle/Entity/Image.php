<?php
namespace Sto\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File,
    Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 *
 * @Vich\Uploadable
 */
Class Image {

    /**
     * @Assert\File(
     *     maxSize="2M",
     *     mimeTypes={"image/png", "image/jpeg", "image/pjpeg"}
     * )
     * @Vich\UploadableField(mapping="deal_image", fileNameProperty="imageName")
     *
     * @var File $image
     */
    protected $image;


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @var string $imageName
     */
    protected $imageName;

    /**
     * Set image
     *
     * @param  string $image
     * @return Deal
     */

    public function preUpload(){
        print "YYYY"; exit;
    }

    public function setImage($image)
    {
        $this->image = $image;
        /*if ($image instanceof UploadedFile) {
            $this->setUpdatedAt(new \DateTime());
        }*/

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

}
