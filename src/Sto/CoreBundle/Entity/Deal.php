<?php

namespace Sto\CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

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
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(name="company_id", type="integer")
     */
    private $companyId;

    /**
     * @ORM\ManyToOne(targetEntity="Company", inversedBy="deals")
     * @ORM\JoinColumn(name="company_id", referencedColumnName="id")
     */
    private $company;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @Assert\Length(max="1000")
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToMany(targetEntity="Sto\CoreBundle\Entity\Dictionary\Work")
     * @ORM\JoinTable(name="deals_services",
     *     joinColumns={@ORM\JoinColumn(name="services_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="deal_id", referencedColumnName="id")}
     * )
     */
    private $services;

    /**
     * @Assert\File(
     *  maxSize="2M",
     *  mimeTypes={"image/png", "image/jpeg", "image/pjpeg"}
     *)
     * @Vich\UploadableField(mapping="deal_image", fileNameProperty="imageName")
     */
    private $image;

    /**
     * @ORM\Column(type="string", name="image_name", nullable=true)
     */
    private $imageName;

    /**
     * @Assert\File(
     *  maxSize="2M",
     *  mimeTypes={"image/png", "image/jpeg", "image/pjpeg"}
     *)
     * @Vich\UploadableField(mapping="deal_image2", fileNameProperty="imageName2")
     */
    private $image2;

    /**
     * @ORM\Column(type="string", name="image_name2", nullable=true)
     */
    private $imageName2;

    /**
     * @Assert\File(
     *  maxSize="2M",
     *  mimeTypes={"image/png", "image/jpeg", "image/pjpeg"}
     *)
     * @Vich\UploadableField(mapping="deal_image3", fileNameProperty="imageName3")
     */
    private $image3;

    /**
     * @ORM\Column(type="string", name="image_name3", nullable=true)
     */
    private $imageName3;

    /**
     * @ORM\Column(name="terms", type="string", length=255, nullable=true)
     */
    private $terms;

    /**
     * @Assert\NotBlank()
     * @Assert\Date()
     * @ORM\Column(name="start_date", type="date")
     */
    private $startDate;

    /**
     * @Assert\NotBlank()
     * @Assert\Date()
     * @ORM\Column(name="end_date", type="date")
     */
    private $endDate;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(name="start_time", type="time")
     */
    private $startTime;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(name="end_time", type="time")
     */
    private $endTime;

    /**
     * @ORM\Column(name="place", type="string", length=255, nullable=true)
     */
    private $place;

    /**
     * @ORM\Column(name="gps", type="string", length=255, nullable=true)
     */
    private $gps;

    /**
     * @ORM\OneToMany(targetEntity="FeedbackDeal", mappedBy="deal", cascade={"all"})
     */
    private $feedbacks;

    /**
     * @ORM\Column(name="contact_information_name", type="string", length=255, nullable=true)
     */
    private $contactInformationName;

    /**
     * @ORM\Column(name="contact_information_phone", type="string", length=255, nullable=true)
     */
    private $contactInformationPhone;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(name="type_id", type="integer", length=255)
     */
    private $typeId;

    /**
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="\Sto\CoreBundle\Entity\Dictionary\DealType", inversedBy="deals")
     * @ORM\JoinColumn(name="type_id", referencedColumnName="id")
     */
    private $type;

    /**
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\Column(name="draft", type="boolean")
     */
    private $draft;

    /**
     * @ORM\ManyToMany(targetEntity="Sto\CoreBundle\Entity\Catalog\Base")
     * @ORM\JoinTable(name="deals_auto",
     *     joinColumns={@ORM\JoinColumn(name="deal_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="auto_id", referencedColumnName="id")}
     * )
     */
    private $auto;

    /**
     * @ORM\ManyToMany(targetEntity="\Sto\CoreBundle\Entity\Dictionary\AutoServices")
     * @ORM\JoinTable(name="deal_auto_service",
     *     joinColumns={@ORM\JoinColumn(name="deal_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="dictionary_id", referencedColumnName="id")}
     * )
     */
    private $autoServices;

    /**
     * @ORM\Column(name="is_vip", type="boolean")
     */
    private $is_vip;

    public function __construct(Company $company = null)
    {
        $this->feedbacks = new ArrayCollection();
        $this->autoServices = new ArrayCollection();
        $this->startDate = new \DateTime('now');
        $this->endDate = new \DateTime('+1week');
        $this->draft = false;
        $this->is_vip = false;

        if ($company) {
            $this->setCompany($company);
            $this->gps = $company->getGps();
        }
    }

    /**
     * Get id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get Draft
     */
    public function getDraft()
    {
        return $this->draft;
    }

    /**
     * Set Draft
     */
    public function setDraft($draft)
    {
        $this->draft = $draft;

        return $this;
    }

    /**
     * Set companyId
     */
    public function setCompanyId($companyId)
    {
        $this->companyId = $companyId;

        return $this;
    }

    /**
     * Get companyId
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
     * Set description
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set services
     */
    public function setServices($services)
    {
        $this->services = $services;

        return $this;
    }

    /**
     * Get services
     */
    public function getServices()
    {
        return $this->services;
    }

    /**
     * Set terms
     */
    public function setTerms($terms)
    {
        $this->terms = $terms;

        return $this;
    }

    /**
     * Get terms
     */
    public function getTerms()
    {
        return $this->terms;
    }

    /**
     * Set startDate
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set endDate
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get endDate
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set time
     */
    public function setStartTime($startTime)
    {
        $this->startTime = $startTime;

        return $this;
    }

    /**
     * Get time
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * Set endTime
     */
    public function setEndTime($endTime)
    {
        $this->endTime = $endTime;

        return $this;
    }

    /**
     * Get endTime
     */
    public function getEndTime()
    {
        return $this->endTime;
    }

    /**
     * Set place
     */
    public function setPlace($place)
    {
        $this->place = $place;

        return $this;
    }

    /**
     * Get place
     */
    public function getPlace()
    {
        return $this->place;
    }

    /**
     * Set contactInformationName
     */
    public function setContactInformationName($contactInformation)
    {
        $this->contactInformationName = $contactInformation;

        return $this;
    }

    /**
     * Get contactInformationName
     */
    public function getContactInformationName()
    {
        return $this->contactInformationName;
    }

    /**
     * Set contactInformationPhone
     */
    public function setContactInformationPhone($contactInformation)
    {
        $this->contactInformationPhone = $contactInformation;

        return $this;
    }

    /**
     * Get contactInformationPhone
     */
    public function getContactInformationPhone()
    {
        return $this->contactInformationPhone;
    }

    /**
     * Set type
     */
    public function setTypeId($typeId)
    {
        $this->typeId = $typeId;

        return $this;
    }

    /**
     * Get type
     */
    public function getTypeId()
    {
        return $this->typeId;
    }

    /**
     * Set type
     */
    public function setType($type)
    {
        $this->type = $type;
        $this->typeId = $type->getId();
        $type->addDeal($this);

        return $this;
    }

    /**
     * Get type
     */
    public function getType()
    {
        return $this->type;
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
     */
    public function getFeedbacks()
    {
        return $this->feedbacks;
    }

    public function __toString()
    {
        return (string) $this->name;
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

    public function getAuto()
    {
        return $this->auto;
    }

    public function setAuto($auto)
    {
        $this->auto = $auto;

        return $this;
    }

    /**
     * Add autoServices
     */
    public function addAutoServices($autoServices)
    {
        $this->autoServices[] = $autoServices;

        return $this;
    }

    /**
     * Remove autoServices
     */
    public function removeAutoServices($autoServices)
    {
        $this->autoServices->removeElement($autoServices);

        return $this;
    }

    /**
     * Get additionalServicautoServiceses
     */
    public function getAutoServices()
    {
        return $this->autoServices;
    }

    /**
     * Set gps
     *
     * @param  string $gps
     * @return Deal
     */
    public function setGps($gps)
    {
        $this->gps = $gps;

        return $this;
    }

    /**
     * Get gps
     *
     * @return string
     */
    public function getGps()
    {
        return $this->gps;
    }

    public function getImagePath()
    {
        return $this->imageName == null ? "/bundles/stocore/images/notimage.png" : "/storage/images/deal_image/{$this->imageName}";
    }

    /**
     * @return bool
     */
    public function getIsVip()
    {
        return $this->is_vip;
    }

    /**
     * @param $value
     * @return $this
     */
    public function setIsVip($value)
    {
        $this->is_vip = $value;
        return $this;
    }
}
