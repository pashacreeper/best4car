<?php

namespace Sto\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File,
    Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\Common\Collections\ArrayCollection;
use Sto\CoreBundle\Entity\Image;

/**
 * Deal
 *
 * @ORM\Table(name="deals")
 * @ORM\Entity(repositoryClass="Sto\CoreBundle\Repository\DealRepository")
 * @Vich\Uploadable
 */
class Deal
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="company_id", type="integer")
     */
    private $companyId;

    /**
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="Company", inversedBy="deals")
     * @ORM\JoinColumn(name="company_id", referencedColumnName="id")
     */
    private $company;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="services", type="string", length=255, nullable=true)
     */
    private $services;

    /**
     *@Assert\File(
     *  maxSize="2M",
     *  mimeTypes={"image/png", "image/jpeg", "image/pjpeg"}
     *)
     * @Vich\UploadableField(mapping="deal_image", fileNameProperty="imageName")
     * @var File $image
     */
    protected $image;

    /**
     * @ORM\Column(type="string", name="image_name", nullable=true)
     */
    protected $imageName;

    /**
     *@Assert\File(
     *  maxSize="2M",
     *  mimeTypes={"image/png", "image/jpeg", "image/pjpeg"}
     *)
     * @Vich\UploadableField(mapping="deal_image2", fileNameProperty="imageName2")
     * @var File $image2
     */
    protected $image2;

    /**
     * @ORM\Column(type="string", name="image_name2", nullable=true)
     */
    protected $imageName2;

    /**
     *@Assert\File(
     *  maxSize="2M",
     *  mimeTypes={"image/png", "image/jpeg", "image/pjpeg"}
     *)
     * @Vich\UploadableField(mapping="deal_image3", fileNameProperty="imageName3")
     * @var File $image3
     */
    protected $image3;

    /**
     * @ORM\Column(type="string", name="image_name3", nullable=true)
     */
    protected $imageName3;

    /**
     * @var string
     *
     * @ORM\Column(name="terms", type="string", length=255, nullable=true)
     */
    private $terms;

    /**
     * @var \DateTime
     *
     * @Assert\NotBlank()
     * @Assert\Date()
     * @ORM\Column(name="start_date", type="date")
     */
    private $startDate;

    /**
     * @var \DateTime
     *
     * @Assert\NotBlank()
     * @Assert\Date()
     * @ORM\Column(name="end_date", type="date")
     */
    private $endDate;

    /**
     * @var \DateTime
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="start_time", type="time")
     */
    private $startTime;

    /**
     * @var \DateTime
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="end_time", type="time")
     */
    private $endTime;

    /**
     * @var string
     *
     * @ORM\Column(name="place", type="string", length=255, nullable=true)
     */
    private $place;

    /**
     * @ORM\OneToMany(targetEntity="FeedbackDeal", mappedBy="deal", cascade={"all"})
     */
    private $feedbacks;

    /**
     * @var string
     *
     * @ORM\Column(name="contact_information", type="string", length=255, nullable=true)
     */
    private $contactInformation;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    public function __construct()
    {
        $this->startDate = new \DateTime('now');
        $this->endDate = new \DateTime('+1week');
        $this->feedbacks = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set companyId
     *
     * @param  integer $companyId
     * @return Deal
     */
    public function setCompanyId($companyId)
    {
        $this->companyId = $companyId;

        return $this;
    }

    /**
     * Get companyId
     *
     * @return integer
     */
    public function getCompanyId()
    {
        return $this->companyId;
    }

    /**
     * Set company
     */
    public function setCompany(Company $company = null)
    {
        $this->company = $company;
        $this->companyId = $company->getId();
        $company->addDeal($this);

        return $this;
    }

    /**
     * Get company
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Set name
     *
     * @param  string $name
     * @return Deal
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param  string $description
     * @return Deal
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set services
     *
     * @param  string $services
     * @return Deal
     */
    public function setServices($services)
    {
        $this->services = $services;

        return $this;
    }

    /**
     * Get services
     *
     * @return string
     */
    public function getServices()
    {
        return $this->services;
    }

    /**
     * Set terms
     *
     * @param  string $terms
     * @return Deal
     */
    public function setTerms($terms)
    {
        $this->terms = $terms;

        return $this;
    }

    /**
     * Get terms
     *
     * @return string
     */
    public function getTerms()
    {
        return $this->terms;
    }

    /**
     * Set startDate
     *
     * @param  \DateTime $startDate
     * @return Deal
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set endDate
     *
     * @param  \DateTime $endDate
     * @return Deal
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get endDate
     *
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set time
     *
     * @param  \DateTime $time
     * @return Deal
     */
    public function setStartTime($startTime)
    {
        $this->startTime = $startTime;

        return $this;
    }

    /**
     * Get time
     *
     * @return \DateTime
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * Set endTime
     *
     * @param  \DateTime $endTime
     * @return Deal
     */
    public function setEndTime($endTime)
    {
        $this->endTime = $endTime;

        return $this;
    }

    /**
     * Get endTime
     *
     * @return \DateTime
     */
    public function getEndTime()
    {
        return $this->endTime;
    }

    /**
     * Set place
     *
     * @param  string $place
     * @return Deal
     */
    public function setPlace($place)
    {
        $this->place = $place;

        return $this;
    }

    /**
     * Get place
     *
     * @return string
     */
    public function getPlace()
    {
        return $this->place;
    }

    /**
     * Set contactInformation
     *
     * @param  string $contactInformation
     * @return Deal
     */
    public function setContactInformation($contactInformation)
    {
        $this->contactInformation = $contactInformation;

        return $this;
    }

    /**
     * Get contactInformation
     *
     * @return string
     */
    public function getContactInformation()
    {
        return $this->contactInformation;
    }

    /**
     * Set type
     *
     * @param  string $type
     * @return Deal
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        $types = [
            'Скидка',
            'Маркетинговое мероприятие',
            'Тест-драйв',
            'Презентация, день открытых дверей.',
            'Распродажа',
            'Сезонное предложение'
        ];

        if ($this->type) {
            return $types[$this->type];
        } else {
            return $this->type;
        }
    }

    /**
     * Add feedback
     */
    public function addFeedbacks(Feedback $feedback)
    {
        $this->feedbacks[] = $feedback;

        return $this;
    }

    /**
     * Remove feedback
     */
    public function removeFeedback(Feedback $feedback)
    {
        $this->feedbacks->removeElement($feedback);
    }

    /**
     * Get project
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFeedbacks()
    {
        return $this->feedbacks;
    }

    public function __toString()
    {
        return $this->name;
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

    /**
     * Set image
     *
     * @param  string $image
     * @return Deal
     */

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    public function setImageName($imageName)
    {
        $this->imageName = $imageName;

        return $this;
    }
    public function getImageName()
    {
        return $this->imageName;
    }

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
    public function getImage2()
    {
        return $this->image2;
    }

    public function setImageName2($imageName2)
    {
        $this->imageName2 = $imageName2;

        return $this;
    }
    public function getImageName2()
    {
        return $this->imageName2;
    }

    public function setImage2($image2)
    {
        $this->image2 = $image2;
        if ($image2 instanceof UploadedFile) {
            $this->setUpdatedAt(new \DateTime());
        }

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage3()
    {
        return $this->image3;
    }

    public function setImageName3($imageName3)
    {
        $this->imageName3 = $imageName3;

        return $this;
    }
    public function getImageName3()
    {
        return $this->imageName3;
    }

    public function setImage3($image3)
    {
        $this->image3 = $image3;
        if ($image3 instanceof UploadedFile) {
            $this->setUpdatedAt(new \DateTime());
        }

        return $this;
    }

}
