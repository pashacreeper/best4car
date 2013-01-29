<?php

namespace Sto\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Company
 *
 * @ORM\Table(name="companies")
 * @ORM\Entity(repositoryClass="Sto\CoreBundle\Repository\CompanyRepository")
 */
class Company
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="slogan", type="string", length=255)
     */
    private $slogan;

    /**
     * @var string
     *
     * @ORM\Column(name="full_name", type="string", length=255)
     */
    private $fullName;

    /**
     * @var string
     *
     * @ORM\Column(name="web", type="string", length=255)
     */
    private $web;

    /**
     * @var string
     *
     * @ORM\Column(name="specialization", type="string", length=255)
     */
    private $specialization;

    /**
     * @var string
     *
     * @ORM\Column(name="services", type="string", length=255)
     */
    private $services;

    /**
     * @var string
     *
     * @ORM\Column(name="additional_services", type="string", length=255)
     */
    private $additionalServices;

    /**
     * @var string
     *
     * @ORM\Column(name="logo", type="string", length=255)
     */
    private $logo;

    /**
     * @var string
     *
     * @ORM\Column(name="working_time", type="string", length=255)
     */
    private $workingTime;

    /**
     * @var string
     *
     * @ORM\Column(name="phones", type="string", length=255)
     */
    private $phones;

    /**
     * @var string
     *
     * @ORM\Column(name="skype", type="string", length=255)
     */
    private $skype;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=255)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="gps", type="string", length=255)
     */
    private $gps;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createt_date", type="date")
     */
    private $createtDate;

    /**
     * @var string
     *
     * @ORM\Column(name="photos", type="string", length=255)
     */
    private $photos;

    /**
     * @var string
     *
     * @ORM\Column(name="social_networks", type="string", length=255)
     */
    private $socialNetworks;

    /**
     * @var float
     *
     * @ORM\Column(name="rating", type="float")
     */
    private $rating;

    /**
     * @var string
     *
     * @ORM\Column(name="reviews", type="string", length=255)
     */
    private $reviews;

    /**
     * @var string
     *
     * @ORM\Column(name="deals", type="string", length=255)
     */
    private $deals;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @var boolean
     *
     * @ORM\Column(name="subscribable", type="boolean")
     */
    private $subscribable;

    /**
     * @var string
     *
     * @ORM\Column(name="hour_price", type="string", length=255)
     */
    private $hourPrice;

    /**
     * @var string
     *
     * @ORM\Column(name="managers", type="string", length=255)
     */
    private $managers;

    /**
     * @var string
     *
     * @ORM\Column(name="administrator_contact_info", type="string", length=255)
     */
    private $administratorContactInfo;

    /**
     * @var boolean
     *
     * @ORM\Column(name="visible", type="boolean")
     */
    private $visible;

    /**
     * @var string
     *
     * @ORM\Column(name="notes", type="string", length=255)
     */
    private $notes;


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
     * @param string $name
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
     * @param string $slogan
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
     * @param string $fullName
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
     * Set web
     *
     * @param string $web
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
     * @param string $specialization
     * @return Company
     */
    public function setSpecialization($specialization)
    {
        $this->specialization = $specialization;

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

    /**
     * Set services
     *
     * @param string $services
     * @return Company
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
     * Set additionalServices
     *
     * @param string $additionalServices
     * @return Company
     */
    public function setAdditionalServices($additionalServices)
    {
        $this->additionalServices = $additionalServices;

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
     * @param string $logo
     * @return Company
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;

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
     * @param string $workingTime
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
     * Set phones
     *
     * @param string $phones
     * @return Company
     */
    public function setPhones($phones)
    {
        $this->phones = $phones;

        return $this;
    }

    /**
     * Get phones
     *
     * @return string
     */
    public function getPhones()
    {
        return $this->phones;
    }

    /**
     * Set skype
     *
     * @param string $skype
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
     * @param string $email
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
     * @param string $address
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
     * @param string $gps
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
     * @param \DateTime $createtDate
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
     * @param string $photos
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
     * @param string $socialNetworks
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
     * @param float $rating
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
     * @param string $reviews
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
     * Set deals
     *
     * @param string $deals
     * @return Company
     */
    public function setDeals($deals)
    {
        $this->deals = $deals;

        return $this;
    }

    /**
     * Get deals
     *
     * @return string
     */
    public function getDeals()
    {
        return $this->deals;
    }

    /**
     * Set description
     *
     * @param string $description
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
     * @param boolean $subscribable
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
     * @param string $hourPrice
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

    /**
     * Set managers
     *
     * @param string $managers
     * @return Company
     */
    public function setManagers($managers)
    {
        $this->managers = $managers;

        return $this;
    }

    /**
     * Get managers
     *
     * @return string
     */
    public function getManagers()
    {
        return $this->managers;
    }

    /**
     * Set administratorContactInfo
     *
     * @param string $administratorContactInfo
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
     * @param boolean $visible
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
     * @param string $notes
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
}
