<?php

namespace Sto\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Sto\UserBundle\Entity\RatingGroup;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Doctrine\Common\Collections\ArrayCollection;
use Sto\CoreBundle\Entity\CompanyManager;

/**
 * Company
 *
 * @ORM\Table(name="companies")
 * @ORM\Entity(repositoryClass="Sto\CoreBundle\Repository\CompanyRepository")
 * @Vich\Uploadable
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
     * @Assert\NotBlank()
     * @ORM\Column(name="name", type="string", length=255)
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
     * @Assert\Url()
     * @ORM\Column(name="web", type="string", length=255, nullable=true)
     */
    private $web;

    /**
     * @ORM\ManyToMany(targetEntity="\Sto\CoreBundle\Entity\Dictionary\Company")
     * @ORM\JoinTable(name="company_dictionary_company_type_parrent",
     *     joinColumns={@ORM\JoinColumn(name="company_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="dictionary_id", referencedColumnName="id")}
     * )
     */
    private $specialization;

    /**
     * @ORM\ManyToMany(targetEntity="\Sto\CoreBundle\Entity\Dictionary\Company")
     * @ORM\JoinTable(name="company_dictionary_company_type_children",
     *     joinColumns={@ORM\JoinColumn(name="company_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="dictionary_id", referencedColumnName="id")}
     * )
     */
    private $services;

    /**
     * @ORM\ManyToMany(targetEntity="\Sto\CoreBundle\Entity\Dictionary\AdditionalService")
     * @ORM\JoinTable(name="company_additional_service",
     *     joinColumns={@ORM\JoinColumn(name="company_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="dictionary_id", referencedColumnName="id")}
     * )
     */
    private $additionalServices;

    /**
     * @Assert\File(
     *     maxSize="2M",
     *     mimeTypes={"image/png", "image/jpeg", "image/pjpeg"}
     * )
     * @Vich\UploadableField(mapping="company_logo", fileNameProperty="logoName")
     */
    protected $logo;

    /**
     * @ORM\Column(name="logo", type="string", length=255, nullable=true)
     */
    protected $logoName;

    /**
     * @ORM\Column(name="working_time", type="array")
     */
    private $workingTime;

    /**
     * @ORM\Column(name="phones", type="array")
     */
    private $phones;

    /**
     * @ORM\Column(name="skype", type="string", length=255, nullable=true)
     */
    private $skype;

    /**
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email.",
     *     checkMX = true
     * )
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(name="address", type="string", length=255)
     */
    private $address;

    /**
     * @ORM\Column(name="gps", type="string", length=255, nullable=true)
     */
    private $gps;

    /**
     * @Assert\Date()
     * @ORM\Column(name="createt_date", type="date")
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
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(name="subscribable", type="boolean")
     */
    private $subscribable;

    /**
     * @ORM\Column(name="hour_price", type="string", length=255, nullable=true)
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
     * @ORM\Column(name="visible", type="boolean")
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
     * @ORM\Column(name="rating_group_id", type="integer", nullable=true)
     */
    protected $ratingGroupId;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Sto\UserBundle\Entity\RatingGroup", inversedBy="companies")
     * @ORM\JoinColumn(name="rating_group_id", referencedColumnName="id")
     */
    protected $ratingGroup;

    /**
     * @ORM\OneToMany(targetEntity="CompanyGallery", mappedBy="company", cascade={"all"})
     */
    private $gallery;

    /**
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\ManyToMany(targetEntity="Sto\UserBundle\Entity\User", inversedBy="companies")
     * @ORM\JoinTable(name="company_user_relationship",
     *     joinColumns={@ORM\JoinColumn(name="company_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")}
     * )
     */
    protected $managers;

    /**
     * @ORM\ManyToOne(targetEntity="\Sto\CoreBundle\Entity\Dictionary\Country",inversedBy="companies")
     * @ORM\JoinColumn(name="city_id", referencedColumnName="id")
     */
    private $city;

    /**
     * @ORM\Column(name="city_id", type="integer")
     */
    private $cityId;

    /**
     * @ORM\ManyToMany(targetEntity="Sto\CoreBundle\Entity\Catalog\Base")
     * @ORM\JoinTable(name="company_autos",
     *     joinColumns={@ORM\JoinColumn(name="auto_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="company_id", referencedColumnName="id")}
     * )
     */
    protected $autos;

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
     * @ORM\OneToMany(targetEntity="Sto\CoreBundle\Entity\CompanyManager", mappedBy="company", cascade={"all"})
     */
    private $companyManager;

    /**
     * @ORM\OneToMany(targetEntity="Sto\UserBundle\Entity\Contacts", mappedBy="company", cascade={"all"})
     */
    private $contacts;

    public function __construct()
    {
        $this->createtDate = new \DateTime('now');
        $this->visible = false;
        $this->subscribable = false;
        $this->deals = new \Doctrine\Common\Collections\ArrayCollection();
        $this->feedbacks = new \Doctrine\Common\Collections\ArrayCollection();
        $this->gallery = new \Doctrine\Common\Collections\ArrayCollection();
        $this->specialization = new \Doctrine\Common\Collections\ArrayCollection();
        $this->service = new \Doctrine\Common\Collections\ArrayCollection();
        $this->additionalServices = new \Doctrine\Common\Collections\ArrayCollection();
        $this->managers = new \Doctrine\Common\Collections\ArrayCollection();
        $this->autos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->companyManager = new \Doctrine\Common\Collections\ArrayCollection();
        $this->contacts = new \Doctrine\Common\Collections\ArrayCollection();
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

    /**
     * Set specialization
     *
     * @param  string  $specialization
     * @return Company
     */
    public function addSpecialization($specialization)
    {
        $this->specialization[] = $specialization;

        return $this;
    }

    public function removeSpecialization($specialization)
    {
        $this->specialization->removeElement($specialization);

        return $this;
    }

    /**
     * Get specialization
     *
     * @return string
     */
    public function getSpecialization()
    {
        return $this->specialization;
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
    public function setWorkingTime($workingTime)
    {
        $this->workingTime = $workingTime;

        return $this;
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

    public function getRatingGroup()
    {
        return $this->ratingGroup;
    }

    public function setRatingGroup(RatingGroup $group)
    {
        $this->ratingGroup = $group;

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
        //$gallery->setCompanyId($this->getId());
        //$gallery->setCompany($this);
        $this->gallery[] = $gallery;

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

    /**
     * Set managers
     */
    public function addManager(\Sto\userBundle\Entity\User $manager)
    {
        $this->managers[] = $manager;

        return $this;
    }

    /**
     * remove manager
     * @param \Sto\UserBundle\Entity\User $manager
     */
    public function removeManager(\Sto\UserBundle\Entity\User $manager)
    {
        $this->managers->removeElement($manager);
    }

    /**
     * Get managers
     *
     */
    public function getManagers()
    {
        return $this->managers;
    }

    public function getArrayManagers()
    {
        $result = array();
        foreach ($this->managers as $key => $value) {
            $result[] = $value;
        }

        return $result;
    }

    public function setManagers($managers)
    {
        $this->managers = $managers;

        return $this;
    }

    public function setCity(\Sto\CoreBundle\Entity\Dictionary\Country $city)
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
    public function addAuto(\Sto\CoreBundle\Entity\Catalog\Base $auto)
    {
        $this->autos[] = $auto;

        return $this;
    }

    /**
     * remove auto
     */
    public function removeAuto(\Sto\CoreBundle\Entity\Catalog\Base $auto)
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
        $manager->setCompany($this);
        $this->companyManager[] = $manager;

        return $this;
    }

    public function removeCompanyManager(CompanyManager $manager)
    {
        $this->companyManager->remove($manager);
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

    public function addContact($contact)
    {
        $this->contacts[] =  $contact;

        return $this;
    }

    public function removeContact($contact)
    {
        $this->contacts->remove($contact);
    }

}
