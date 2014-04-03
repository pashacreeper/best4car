<?php

namespace Sto\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Doctrine\Common\Collections\ArrayCollection;
use Sto\CoreBundle\Entity\CompanyManager;
use Sto\CoreBundle\Entity\CompanyType;

/**
 * Company
 *
 * @ORM\Table(name="companies", indexes={@ORM\Index(name="companies_search_idx", columns={"name", "full_name", "slogan"})})
 * @ORM\Entity(repositoryClass="Sto\CoreBundle\Repository\CompanyRepository")
 * @Vich\Uploadable
 * @ORM\HasLifecycleCallbacks
 */
class Company
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
     * @ORM\Column(name="slogan", type="string", length=255, nullable=true)
     */
    private $slogan;

    /**
     * @ORM\Column(name="full_name", type="string", length=255, nullable=true)
     */
    private $fullName;

    /**
     * @ORM\Column(name="web", type="string", length=255, nullable=true)
     */
    private $web;

    /**
     * @ORM\OneToMany(targetEntity="\Sto\CoreBundle\Entity\CompanySpecialization", mappedBy="company", cascade={"all"}, orphanRemoval=true)
     */
    private $specializations;

    /**
     * @ORM\OneToMany(targetEntity="\Sto\CoreBundle\Entity\CompanyWorkingTime", mappedBy="company", cascade={"all"}, orphanRemoval=true)
     */
    private $workingTime;

    /**
     * @ORM\ManyToMany(targetEntity="\Sto\CoreBundle\Entity\Dictionary\AdditionalService")
     * @ORM\JoinTable(name="company_additional_service",
     *     joinColumns={@ORM\JoinColumn(name="company_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="dictionary_id", referencedColumnName="id")}
     * )
     */
    private $additionalServices;

    /**
     * @ORM\ManyToMany(targetEntity="\Sto\CoreBundle\Entity\AutoServices")
     * @ORM\JoinTable(name="company_auto_service",
     *     joinColumns={@ORM\JoinColumn(name="company_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="dictionary_id", referencedColumnName="id")}
     * )
     */
    private $autoServices;

    /**
     * @Assert\File(
     *     maxSize="1M",
     *     mimeTypes={"image/png", "image/jpeg", "image/pjpeg", "image/gif"},
     *     uploadIniSizeErrorMessage="", mimeTypesMessage=""
     * )
     * @Vich\UploadableField(mapping="company_logo", fileNameProperty="logoName")
     */
    protected $logo;

    public $adminLogoDelete = false;

    /**
     * @ORM\Column(name="logo", type="string", length=255, nullable=true)
     */
    protected $logoName;

    /**
    * @ORM\OneToMany(targetEntity="Sto\CoreBundle\Entity\CompanyPhone", mappedBy="company", cascade={"all"})
    */
    private $phones;

    /**
     * @ORM\Column(name="skype", type="string", length=255, nullable=true)
     */
    private $skype;

    /**
     * @Assert\Email(
     *     message = "Необходимо указать правильный email адрес",
     *     checkMX = true
     * )
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(name="address", type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @ORM\Column(name="gps", type="string", length=255, nullable=true)
     */
    private $gps;

    /**
     * @ORM\Column(name="createt_date", type="date", nullable=true)
     */
    private $createtDate;

    /**
     * @ORM\Column(name="photos", type="string", length=255, nullable=true)
     */
    private $photos;

    /**
     * @ORM\Column(name="social_networks", type="string", length=255, nullable=true)
     */
    private $socialNetworks;

    /**
     * @ORM\Column(name="rating", type="float", nullable=true)
     */
    private $rating;

    /**
     * @ORM\Column(name="reviews", type="string", length=255, nullable=true)
     */
    private $reviews;

    /**
     * @ORM\OneToMany(targetEntity="Deal", mappedBy="company", cascade={"all"})
     */
    private $deals;

    /**
     * @ORM\Column(name="description", type="text", nullable=true)
     * @Assert\Length(
     *     max = "1250",
     *     maxMessage = "Длина описания компании может быть максимум 1250 символов."
     * )
     */
    private $description;

    /**
     * @ORM\Column(name="subscribable", type="boolean")
     */
    private $subscribable;

    /**
     * @ORM\Column(name="hour_price", type="integer", nullable=true)
     */
    private $hourPrice;

    /**
     * @ORM\Column(name="currency_id", type="integer", length=255, nullable=true)
     */
    private $currencyId;

    /**
     * @ORM\ManyToOne(targetEntity="\Sto\CoreBundle\Entity\Dictionary\Currency")
     * @ORM\JoinColumn(name="currency_id", referencedColumnName="id")
     */
    private $currency;

    /**
     * @ORM\Column(name="administrator_contact_info", type="string", length=255, nullable=true)
     */
    private $administratorContactInfo;

    /**
     * @ORM\Column(name="visible", type="boolean", nullable=true)
     */
    private $visible;

    /**
     * @ORM\Column(name="notes", type="string", length=255, nullable=true)
     */
    private $notes;

    /**
     * @ORM\OneToMany(targetEntity="FeedbackCompany", mappedBy="company", cascade={"all"})
     */
    private $feedbacks;

    /**
     * @ORM\ManyToMany(targetEntity="Sto\UserBundle\Entity\Group", inversedBy="companies")
     * @ORM\JoinTable(name="company_group_relationship",
     *     joinColumns={@ORM\JoinColumn(name="company_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
     * )
     */
    protected $groups;

    /**
     * @ORM\OneToMany(targetEntity="CompanyGallery", mappedBy="company", cascade={"all"}, orphanRemoval=true)
     */
    private $gallery;

    /**
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity="\Sto\CoreBundle\Entity\Country",inversedBy="companies")
     * @ORM\JoinColumn(name="city_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    private $city;

    /**
     * @ORM\Column(name="city_id", type="integer", nullable=true)
     */
    private $cityId;

    /**
     * @ORM\ManyToMany(targetEntity="Sto\CoreBundle\Entity\Mark")
     * @ORM\JoinTable(name="company_autos",
     *     joinColumns={@ORM\JoinColumn(name="company_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="auto_id", referencedColumnName="id")}
     * )
     */
    protected $autos;

    /**
     * @ORM\Column(name="all_auto", type="boolean", nullable=true)
     */
    private $allAuto = false;

    /**
     * @ORM\Column(name="vk_link", type="string", length=255, nullable=true)
     */
    private $linkVK;

    /**
     * @ORM\Column(name="twitter_link", type="string", length=255, nullable=true)
     */
    private $linkTW;

    /**
     * @ORM\Column(name="fb_link", type="string", length=255, nullable=true)
     */
    private $linkFB;

    /**
     * @ORM\OneToMany(targetEntity="Sto\CoreBundle\Entity\CompanyManager", mappedBy="company", cascade={"all"}, orphanRemoval=true)
     */
    private $companyManager;

    /**
     * @ORM\OneToMany(targetEntity="Sto\CoreBundle\Entity\CompanyContacts", mappedBy="company", cascade={"all"}, orphanRemoval=true)
     */
    private $contacts;

    /**
     * @ORM\Column(name="registred_fully", type="boolean", nullable=true)
     */
    private $registredFully;

    /**
     * @ORM\Column(name="registration_step", type="string", length=255, nullable=true)
     */
    private $registrationStep;

    /**
     * @ORM\Column(name="vip", type="boolean", nullable=true)
     */
    private $vip;

    /**
    * @ORM\OneToMany(targetEntity="Sto\CoreBundle\Entity\CompanyEmail", mappedBy="company", cascade={"all"}, orphanRemoval=true)
    */
    private $emails;

    /**
     * @ORM\ManyToOne(targetEntity="Sto\CoreBundle\Entity\CompanyType")
     */
    private $type;

    /**
     * Set registrationStep
     *
     * @param  string  $registrationStep
     * @return Company
     */
    public function setRegistrationStep($registrationStep)
    {
        $this->registrationStep = $registrationStep;

        return $this;
    }

    /**
     * Get registrationStep
     *
     * @return string
     */
    public function getRegistrationStep()
    {
        return $this->registrationStep;
    }

    /**
     * Set registredFully
     *
     * @param  boolean $registredFully
     * @return Company
     */
    public function setRegistredFully($registredFully)
    {
        $this->registredFully = $registredFully;

        return $this;
    }

    /**
     * Get registredFully
     *
     * @return boolean
     */
    public function getRegistredFully()
    {
        return $this->registredFully;
    }

    public function __construct()
    {
        $this->createtDate = new \DateTime('now');
        $this->visible = true;
        $this->subscribable = false;
        $this->deals = new ArrayCollection();
        $this->feedbacks = new ArrayCollection();
        $this->gallery = new ArrayCollection();
        $this->specializations = new ArrayCollection();
        $this->service = new ArrayCollection();
        $this->additionalServices = new ArrayCollection();
        $this->autoServices = new ArrayCollection();
        $this->autos = new ArrayCollection();
        $this->companyManager = new ArrayCollection();
        $this->contacts = new ArrayCollection();
        $this->workingTime = new ArrayCollection();
        $this->emails = new ArrayCollection();
    }

    public function getAllAuto()
    {
        return $this->allAuto;
    }

    public function setAllAuto($value)
    {
        $this->allAuto = $value;
        if ($value) {
            $this->autos = new ArrayCollection();
        }

        return$this;
    }

    public function getAdminLogoDelete()
    {
        return $this->adminLogoDelete;
    }

    public function setAdminLogoDelete($value)
    {
        $this->adminLogoDelete = $value;
        if ($value) {
            $this->logoName = null;
        }

        return $this;
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
     * Set name
     *
     * @param  string  $name
     * @return Company
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
     * Set slogan
     *
     * @param  string  $slogan
     * @return Company
     */
    public function setSlogan($slogan)
    {
        $this->slogan = $slogan;

        return $this;
    }

    /**
     * Get slogan
     *
     * @return string
     */
    public function getSlogan()
    {
        return $this->slogan;
    }

    /**
     * Set fullName
     *
     * @param  string  $fullName
     * @return Company
     */
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;

        return $this;
    }

    /**
     * Get fullName
     *
     * @return string
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * Set Phones
     */
    public function setPhones($phones)
    {
        $this->phones = $phones;

        return $this;
    }

    /**
     * Get project
     */
    public function getPhones()
    {
        return $this->phones;
    }

    /**
     * Set web
     *
     * @param  string  $web
     * @return Company
     */
    public function setWeb($web)
    {
        $web = str_replace(['http://', 'https://'], '', $web);
        $web = rtrim($web, '/');
        $web = strtolower($web);

        $this->web = $web;

        return $this;
    }

    /**
     * Get web
     *
     * @return string
     */
    public function getWeb()
    {
        return $this->web;
    }

    public function getWebLink()
    {
        $web = $this->web;

        if (strpos($web, 'http://') === false && strpos($web, 'https://') === false) {
            $web = 'http://'.$web;
        }

        return $web;
    }

    /**
     * Set specialization
     *
     * @param  string  $specializations
     * @return Company
     */
    public function addSpecialization($specialization)
    {
        $this->specializations[] = $specialization->setCompany($this);

        return $this;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function setTypeFromSpecs()
    {
        if(!$this->specializations->isEmpty()) {
            $this->setType($this->specializations->first()->getType());
        }
    }

    public function removeSpecialization($specialization)
    {
        $this->specializations->removeElement($specialization);

        return $this;
    }

    /**
     * Get specialization
     *
     * @return string
     */
    public function getSpecializations()
    {
        return $this->specializations;
    }

    public function setSpecializations($value)
    {
        $this->specializations = $value;

        return $this;
    }

    public function setServices($services)
    {
        $this->services = $services;

        return $this;
    }

    /**
     * Set services
     *
     * @param  string  $services
     * @return Company
     */
    public function addServices($service)
    {
        $this->services[] = $service;

        return $this;
    }

    public function removeServices($service)
    {
        $this->services->removeElement($service);

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
     * Set additionalServices
     *
     * @param  string  $additionalServices
     * @return Company
     */
    public function addAdditionalServices($additionalService)
    {
        $this->additionalServices[] = $additionalService;

        return $this;
    }

    public function removeAdditionalServices($additionalService)
    {
        $this->additionalServices->removeElement($additionalService);

        return $this;
    }

    /**
     * Get additionalServices
     *
     * @return string
     */
    public function getAdditionalServices()
    {
        return $this->additionalServices;
    }

    /**
     * Set autoServices
     *
     * @param  string  $autoServices
     * @return Company
     */
    public function addAutoServices($autoServices)
    {
        $this->autoServices[] = $autoServices;

        return $this;
    }

    public function removeAutoServices($autoServices)
    {
        $this->autoServices->removeElement($autoServices);

        return $this;
    }

    /**
     * Get additionalServicautoServiceses
     *
     * @return string
     */
    public function getAutoServices()
    {
        return $this->autoServices;
    }

    /**
     * Set logo
     *
     * @param  string  $logo
     * @return Company
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;
        if ($logo instanceof UploadedFile) {
            $this->setUpdatedAt(new \DateTime());
        }

        return $this;
    }

    /**
     * Get logo
     *
     * @return string
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * Set workingTime
     *
     * @param  string  $workingTime
     * @return Company
     */
    public function addWorkingTime($workingTime)
    {
        $this->workingTime[] = $workingTime->setCompany($this);

        return $this;
    }

    public function removeWorkingTime($value)
    {
        $this->workingTime->removeElement($value);
    }

    /**
     * Get workingTime
     *
     * @return string
     */
    public function getWorkingTime()
    {
        return $this->workingTime;
    }

    /**
     * Set skype
     *
     * @param  string  $skype
     * @return Company
     */
    public function setSkype($skype)
    {
        $this->skype = $skype;

        return $this;
    }

    /**
     * Get skype
     *
     * @return string
     */
    public function getSkype()
    {
        return $this->skype;
    }

    /**
     * Set email
     *
     * @param  string  $email
     * @return Company
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set address
     *
     * @param  string  $address
     * @return Company
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set gps
     *
     * @param  string  $gps
     * @return Company
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

    /**
     * Set createtDate
     *
     * @param  \DateTime $createtDate
     * @return Company
     */
    public function setCreatetDate($createtDate)
    {
        $this->createtDate = $createtDate;

        return $this;
    }

    /**
     * Get createtDate
     *
     * @return \DateTime
     */
    public function getCreatetDate()
    {
        return $this->createtDate;
    }

    /**
     * Set photos
     *
     * @param  string  $photos
     * @return Company
     */
    public function setPhotos($photos)
    {
        $this->photos = $photos;

        return $this;
    }

    /**
     * Get photos
     *
     * @return string
     */
    public function getPhotos()
    {
        return $this->photos;
    }

    /**
     * Set socialNetworks
     *
     * @param  string  $socialNetworks
     * @return Company
     */
    public function setSocialNetworks($socialNetworks)
    {
        $this->socialNetworks = $socialNetworks;

        return $this;
    }

    /**
     * Get socialNetworks
     *
     * @return string
     */
    public function getSocialNetworks()
    {
        return $this->socialNetworks;
    }

    /**
     * Set rating
     *
     * @param  float   $rating
     * @return Company
     */
    public function setRating($rating)
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * Get rating
     *
     * @return float
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * Set reviews
     *
     * @param  string  $reviews
     * @return Company
     */
    public function setReviews($reviews)
    {
        $this->reviews = $reviews;

        return $this;
    }

    /**
     * Get reviews
     *
     * @return string
     */
    public function getReviews()
    {
        return $this->reviews;
    }

    /**
     * Set description
     *
     * @param  string  $description
     * @return Company
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
     * Set subscribable
     *
     * @param  boolean $subscribable
     * @return Company
     */
    public function setSubscribable($subscribable)
    {
        $this->subscribable = $subscribable;

        return $this;
    }

    /**
     * Get subscribable
     *
     * @return boolean
     */
    public function getSubscribable()
    {
        return $this->subscribable;
    }

    /**
     * Set hourPrice
     *
     * @param  string  $hourPrice
     * @return Company
     */
    public function setHourPrice($hourPrice)
    {
        $this->hourPrice = $hourPrice;

        return $this;
    }

    /**
     * Get hourPrice
     *
     * @return string
     */
    public function getHourPrice()
    {
        return $this->hourPrice;
    }

    public function setCurrency($currency)
    {
        $this->currency = $currency;

        return $this;
    }

    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Set administratorContactInfo
     *
     * @param  string  $administratorContactInfo
     * @return Company
     */
    public function setAdministratorContactInfo($administratorContactInfo)
    {
        $this->administratorContactInfo = $administratorContactInfo;

        return $this;
    }

    /**
     * Get administratorContactInfo
     *
     * @return string
     */
    public function getAdministratorContactInfo()
    {
        return $this->administratorContactInfo;
    }

    /**
     * Set visible
     *
     * @param  boolean $visible
     * @return Company
     */
    public function setVisible($visible)
    {
        $this->visible = $visible;

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

    /**
     * Set notes
     *
     * @param  string  $notes
     * @return Company
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;

        return $this;
    }

    /**
     * Get notes
     *
     * @return string
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * Add deal
     */
    public function addDeal(Deal $deal)
    {
        $this->deals[] = $deal;

        return $this;
    }

    /**
     * Remove deal
     */
    public function removeDeal(Deal $deal)
    {
        $this->deals->removeElement($deal);
    }

    /**
     * Get project
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDeals()
    {
        return $this->deals;
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
        return (string) $this->name;
    }

    public function getGroups()
    {
        return $this->groups;
    }

    public function setGroups($group)
    {
        $this->groups = $group;

        return $this;
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

    public function setLogoName($logoName)
    {
        $this->logoName = $logoName;

        return $this;
    }

    public function getLogoName()
    {
        return $this->logoName;
    }

    /**
     * Add gallery
     */
    public function addGallery(CompanyGallery $gallery)
    {
        $this->gallery[] = $gallery->setCompany($this);

        return $this;
    }

    /**
     * Remove gallery
     */
    public function removeGallery(CompanyGallery $gallery)
    {
        $this->gallery->removeElement($gallery);
    }

    public function setGallery($gallery)
    {
        $this->gallery = $gallery;

        return $this;
    }

    /**
     * Get project
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGallery()
    {
        return $this->gallery;
    }

    public function setCity(\Sto\CoreBundle\Entity\Country $city)
    {
        $this->city = $city;
        $city->addCompany($this);

        return $this;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function setCityId($cityId)
    {
        $this->cityId = $cityId;

        return $this;
    }

    public function getCityId()
    {
        return $this->cityId;
    }

    /**
     * Set auto
     */
    public function addAuto(\Sto\CoreBundle\Entity\Mark $auto)
    {
        if ($this->allAuto == false) {
            $this->autos[] = $auto;
        }

        return $this;
    }

    /**
     * remove auto
     */
    public function removeAuto(\Sto\CoreBundle\Entity\Mark $auto)
    {
        $this->autos->removeElement($auto);
    }

    /**
     * Get managers
     *
     */
    public function getAutos()
    {
        return $this->autos;
    }

    public function getLinkVK()
    {
        return $this->linkVK;
    }

    public function setLinkVK($link)
    {
        $this->linkVK = $link;

        return $this;
    }

    public function getLinkFB()
    {
        return $this->linkFB;
    }

    public function setLinkFB($link)
    {
        $this->linkFB = $link;

        return $this;
    }

    public function getLinkTW()
    {
        return $this->linkTW;
    }

    public function setLinkTW($link)
    {
        $this->linkTW = $link;

        return $this;
    }

    public function getCompanyManager()
    {
        return $this->companyManager;
    }

    public function addCompanyManager(CompanyManager $manager)
    {
        $this->companyManager[] = $manager->setCompany($this);

        return $this;
    }

    public function removeCompanyManager(CompanyManager $manager)
    {

        $this->companyManager->removeElement($manager);
    }

    public function setCompanyManager($managers)
    {
        $this->companyManager = $managers;

        return $this;
    }

    public function getContacts()
    {
        return $this->contacts;
    }

    public function addContact(CompanyContacts $contact)
    {
        $contact->setCompany($this);
        $this->contacts[] =  $contact;

        return $this;
    }

    public function removeContact($contact)
    {
        $this->contacts->removeElement($contact);
    }

    public function getImagePath()
    {
        return $this->logoName == null ? "/bundles/stocore/images/notimage.png" : "/storage/images/company_logo/{$this->logoName}";
    }

    /**
     * Set currencyId
     *
     * @param  integer $currencyId
     * @return Company
     */
    public function setCurrencyId($currencyId)
    {
        $this->currencyId = $currencyId;

        return $this;
    }

    /**
     * Get currencyId
     *
     * @return integer
     */
    public function getCurrencyId()
    {
        return $this->currencyId;
    }

    /**
     * Add additionalServices
     *
     * @param  \Sto\CoreBundle\Entity\Dictionary\AdditionalService $additionalServices
     * @return Company
     */
    public function addAdditionalService(\Sto\CoreBundle\Entity\Dictionary\AdditionalService $additionalServices)
    {
        $this->additionalServices[] = $additionalServices;

        return $this;
    }

    /**
     * Remove additionalServices
     *
     * @param \Sto\CoreBundle\Entity\Dictionary\AdditionalService $additionalServices
     */
    public function removeAdditionalService(\Sto\CoreBundle\Entity\Dictionary\AdditionalService $additionalServices)
    {
        $this->additionalServices->removeElement($additionalServices);
    }

    /**
     * Add autoServices
     *
     * @param  \Sto\CoreBundle\Entity\AutoServices $autoServices
     * @return Company
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
     * Add feedbacks
     *
     * @param  \Sto\CoreBundle\Entity\FeedbackCompany $feedbacks
     * @return Company
     */
    public function addFeedback(\Sto\CoreBundle\Entity\FeedbackCompany $feedbacks)
    {
        $this->feedbacks[] = $feedbacks;

        return $this;
    }

    /**
     * Add groups
     *
     * @param  \Sto\UserBundle\Entity\Group $groups
     * @return Company
     */
    public function addGroup(\Sto\UserBundle\Entity\Group $groups)
    {
        $this->groups[] = $groups;

        return $this;
    }

    /**
     * Remove groups
     *
     * @param \Sto\UserBundle\Entity\Group $groups
     */
    public function removeGroup(\Sto\UserBundle\Entity\Group $groups)
    {
        $this->groups->removeElement($groups);
    }

    public function isRegistredFully()
    {
        return $this->registredFully;
    }

    /**
     * Set vip
     *
     * @param  boolean $vip
     * @return Company
     */
    public function setVip($vip)
    {
        $this->vip = $vip;

        return $this;
    }

    /**
     * Get vip
     *
     * @return boolean
     */
    public function getVip()
    {
        return $this->vip;
    }

    /**
     * Add emails
     *
     * @param  \Sto\CoreBundle\Entity\CompanyEmail $emails
     * @return Company
     */
    public function addEmail(\Sto\CoreBundle\Entity\CompanyEmail $emails)
    {
        $this->emails[] = $emails;

        return $this;
    }

    /**
     * Remove emails
     *
     * @param \Sto\CoreBundle\Entity\CompanyEmail $emails
     */
    public function removeEmail(\Sto\CoreBundle\Entity\CompanyEmail $emails)
    {
        $this->emails->removeElement($emails);
    }

    /**
     * Get emails
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEmails()
    {
        return $this->emails;
    }

    /**
     * Add phones
     *
     * @param  \Sto\CoreBundle\Entity\CompanyPhone $phones
     * @return Company
     */
    public function addPhone(\Sto\CoreBundle\Entity\CompanyPhone $phones)
    {
        $this->phones[] = $phones;

        return $this;
    }

    /**
     * Remove phones
     *
     * @param \Sto\CoreBundle\Entity\CompanyPhone $phones
     */
    public function removePhone(\Sto\CoreBundle\Entity\CompanyPhone $phones)
    {
        $this->phones->removeElement($phones);
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType(CompanyType $value)
    {
        $this->type = $value;

        return $this;
    }

    public function getNameWithAddress()
    {
        return "{$this->name} - {$this->address}";
    }
}
