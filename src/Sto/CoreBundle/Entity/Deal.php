<?php

namespace Sto\CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Deal
 *
 * @ORM\Table(name="deals", indexes={@ORM\Index(name="deals_search_idx", columns={"name", "terms"})})
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
     * @Assert\Length(max="75")
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max="1250")
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
     *  maxSize="5M",
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
     *  maxSize="5M",
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
     *  maxSize="5M",
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
     * @ORM\Column(name="start_time", type="time", nullable=true)
     */
    private $startTime;

    /**
     * @ORM\Column(name="end_time", type="time", nullable=true)
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
     * @ORM\ManyToMany(targetEntity="Company")
     * @ORM\JoinTable(name="deal_additional_companies",
     *     joinColumns={@ORM\JoinColumn(name="deal_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="dictionary_id", referencedColumnName="id")}
     * )
     */
    private $additionalCompanies;

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
     * @ORM\ManyToOne(targetEntity="\Sto\CoreBundle\Entity\DealType", inversedBy="deals")
     * @ORM\JoinColumn(name="type_id", referencedColumnName="id")
     */
    private $type;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\Column(name="draft", type="boolean")
     */
    private $draft;

    /**
     * @ORM\ManyToMany(targetEntity="Sto\CoreBundle\Entity\Mark")
     * @ORM\JoinTable(name="deals_auto",
     *     joinColumns={@ORM\JoinColumn(name="deal_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="auto_id", referencedColumnName="id")}
     * )
     */
    private $auto;

    /**
     * @ORM\ManyToMany(targetEntity="\Sto\CoreBundle\Entity\AutoServices")
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

    /**
     * @ORM\Column(name="on_company_place", type="boolean")
     */
    private $onCompanyPlace;

    /**
     * @ORM\Column(name="all_auto", type="boolean", nullable=true)
     */
    private $allAuto = false;

    public function __construct(Company $company = null)
    {
        $this->feedbacks = new ArrayCollection();
        $this->autoServices = new ArrayCollection();
        $this->additionalCompanies = new ArrayCollection();
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
     * @Assert\True(message = "Дата начала акции должна быть меньше даты окончания")
     */
    public function isEndDateValid()
    {
        return $this->startDate->getTimestamp() < $this->endDate->getTimestamp();
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

    /**
     * Add services
     *
     * @param  \Sto\CoreBundle\Entity\Dictionary\Work $services
     * @return Deal
     */
    public function addService(\Sto\CoreBundle\Entity\Dictionary\Work $services)
    {
        $this->services[] = $services;

        return $this;
    }

    /**
     * Remove services
     *
     * @param \Sto\CoreBundle\Entity\Dictionary\Work $services
     */
    public function removeService(\Sto\CoreBundle\Entity\Dictionary\Work $services)
    {
        $this->services->removeElement($services);
    }

    /**
     * Add feedbacks
     *
     * @param  \Sto\CoreBundle\Entity\FeedbackDeal $feedbacks
     * @return Deal
     */
    public function addFeedback(\Sto\CoreBundle\Entity\FeedbackDeal $feedbacks)
    {
        $this->feedbacks[] = $feedbacks;

        return $this;
    }

    /**
     * Add auto
     *
     * @param  \Sto\CoreBundle\Entity\Mark $auto
     * @return Deal
     */
    public function addAuto(\Sto\CoreBundle\Entity\Mark $auto)
    {
        $this->auto[] = $auto;

        return $this;
    }

    /**
     * Remove auto
     *
     * @param \Sto\CoreBundle\Entity\Mark $auto
     */
    public function removeAuto(\Sto\CoreBundle\Entity\Mark $auto)
    {
        $this->auto->removeElement($auto);
    }

    /**
     * Add autoServices
     *
     * @param  \Sto\CoreBundle\Entity\AutoServices $autoServices
     * @return Deal
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
     * Set createdAt
     *
     * @param  \DateTime $createdAt
     * @return Deal
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set onCompanyPlace
     *
     * @param boolean $onCompanyPlace
     */
    public function setOnCompanyPlace($onCompanyPlace)
    {
        $this->onCompanyPlace = $onCompanyPlace;
    }

    /**
     * Get onCompanyPlace
     *
     * @return boolean
     */
    public function getOnCompanyPlace()
    {
        return $this->onCompanyPlace;
    }

    /**
     * Add additional company
     */
    public function addAdditionalCompany(Feedback $company)
    {
        $this->additionalCompanies[] = $company;

        return $this;
    }

    /**
     * Remove additional company
     */
    public function removeAdditionalCompany($company)
    {
        $this->additionalCompanies->removeElement($feedback);
    }

    /**
     * Get additional companies
     */
    public function getAdditionalCompanies()
    {
        return $this->additionalCompanies;
    }

    /**
     * @return mixed
     */
    public function getAllAuto()
    {
        return $this->allAuto;
    }

    /**
     * @param mixed $allAuto
     */
    public function setAllAuto($allAuto)
    {
        $this->allAuto = $allAuto;
    }

    public function getSelectedMarks($selectedMarks)
    {
        $marks = [];

        foreach ($this->auto as $mark) {
            if (in_array($mark->getId(), $selectedMarks)) {
                $marks[] = $mark;
            }
        }

        return array_slice($marks, 0, 3);
    }

    public function getOtherMarks($selectedMarks)
    {
        $marks = [];

        foreach ($this->auto as $mark) {
            if (!in_array($mark->getId(), $selectedMarks)) {
                $marks[] = $mark;
            }
        }

        return array_slice($marks, 0, 3 - count($this->getSelectedMarks($selectedMarks)));
    }
}
