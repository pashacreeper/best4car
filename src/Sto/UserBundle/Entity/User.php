<?php
// src/Acme/UserBundle/Entity/User.php

namespace Sto\UserBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity,
    Symfony\Component\Validator\Constraints as Assert;

use Sto\CoreBundle\Entity\Dictionary,
    Sto\UserBundle\Entity\Group,
    Sto\UserBundle\Entity\RatingGroup;
use Symfony\Component\HttpFoundation\File\File,
    Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 * @Vich\Uploadable
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * UniqueEntity("email") ----- this is gives ERROR
     */
    protected $id;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="first_name", type="string", length=255)
     *
     */
    protected $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=255)
     *
     */
    protected $lastName;

//  username - nickname

    /**
     * @var integer
     *
     * @ORM\Column(name="rating", type="integer", nullable=true)
     */
    protected $rating;

    /**
     * @var integer
     *
     * @ORM\Column(name="rating_group_id", type="integer")
     */
    protected $ratingGroupId;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Sto\UserBundle\Entity\RatingGroup", inversedBy="users")
     * @ORM\JoinColumn(name="rating_group_id", referencedColumnName="id")
     */
    protected $ratingGroup;

    /**
     * @var string
     *
     * @ORM\Column(name="phone_number", type="string", length=255, nullable=true)
     */
    private $phoneNumber;

    /**
     *
     * @Assert\File(
     *     maxSize="2M",
     *     mimeTypes={"image/png", "image/jpeg", "image/pjpeg"}
     * )
     * @Vich\UploadableField(mapping="user_photo", fileNameProperty="avatarUrl")
     *
     * @var File $avatar
     */
    protected $avatar;
    /**
     * @var string $avatarUrl
     *
     * @ORM\Column(name="avatar_url", type="string", length=255, nullable=true)
     *
     */
    protected $avatarUrl;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birth_date", type="date", nullable=true)
     */
    private $birthDate;

    /**
     * @Assert\Choice(callback = "getGenders")
     * @ORM\Column(name="gender", type="string", nullable=true)
     */
    protected $gender;

    /**
     * @var integer
     *
     * @ORM\Column(name="city_id", type="integer", nullable=true)
     */
    protected $cityId;

    /**
     * @var string
     *
     * @ORM\Column(name="link_vk", type="string", length=255, nullable=true)
     */
    private $linkVK;

    /**
     * @var string
     *
     * @ORM\Column(name="link_fb", type="string", length=255, nullable=true)
     */
    private $linkFB;

    /**
     * @var string
     *
     * @ORM\Column(name="link_gp", type="string", length=255, nullable=true)
     */
    private $linkGP;

    /**
     * @var string
     *
     * @ORM\Column(name="auto_profiles_links", type="string", length=255, nullable=true)
     */
    private $autoProfilesLinks;

    /**
     * @var string
     *
     * @ORM\Column(name="link_garage", type="string", length=255, nullable=true)
     */
    private $linkGarage;

    /**
     * @var integer
     *
     * @ORM\Column(name="content_group_id", type="integer", nullable=true)
     */
    protected $contentGroupId;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @var integer
     *
     * @ORM\Column(name="job_id", type="integer", nullable=true)
     */
    protected $jobId;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Sto\CoreBundle\Entity\Dictionary")
     * @ORM\JoinColumn(name="job_id", referencedColumnName="id")
     */
    private $job;

    /**
     * @var string
     *
     * @ORM\Column(name="subscriptions", type="string", length=255, nullable=true)
     */
    private $subscriptions;

    /**
     * @var string
     *
     * @ORM\Column(name="feedbacks", type="string", length=255, nullable=true)
     */
    private $feedbacks;

    /**
     * @var string
     *
     * @ORM\Column(name="requests", type="string", length=255, nullable=true)
     */
    private $requests;

    /**
     * @ORM\ManyToMany(targetEntity="Sto\UserBundle\Entity\Group", inversedBy="users")
     * @ORM\JoinTable(name="fos_user_user_group",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
     * )
     */
    protected $groups;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    public function __construct()
    {
        parent::__construct();
        // your own logic
        $this->gender = 'male';
    }

    public static function getGenders()
    {
        return array('male', 'female');
    }

// Getters & Setters ---------------------

    /**
     * Set firstName
     *
     * @param  string $firstName
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param  string $lastName
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set rating
     *
     * @param  string $rating
     * @return User
     */
    public function setRating($rating)
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * Get rating
     *
     * @return string
     */
    public function getRating()
    {
        return $this->rating;
    }

    //ratingGroupId
    /**
     * Set ratingGroupId
     *
     * @param  string $ratingGroupId
     * @return User
     */
    public function setRatingGroupId($ratingGroupId)
    {
        $this->ratingGroupId = $ratingGroupId;

        return $this;
    }

    /**
     * Get ratingGroupId
     *
     * @return string
     */
    public function getRatingGroupId()
    {
        return $this->ratingGroupId;
    }

    /**
     * Set phone_number
     *
     * @param  string $phoneNumber
     * @return User
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * Get phone_number
     *
     * @return string
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
        if ($avatar instanceof UploadedFile) {
            $this->setUpdatedAt(new \DateTime());
        }

        return $this;
    }

    public function getAvatar()
    {
        return $this->avatar;
    }

    // avatarUrl
    /**
     * Set avatarUrl
     *
     * @param  string $avatarUrl
     * @return User
     */
    public function setAvatarUrl($avatarUrl)
    {
        $this->avatarUrl = $avatarUrl;

        return $this;
    }

    /**
     * Get avatarUrl
     *
     * @return string
     */
    public function getAvatarUrl()
    {
        return $this->avatarUrl;
    }

    // birthDate
    /**
     * Set birthDate
     *
     * @param  string $birthDate
     * @return User
     */
    public function setBirthDate($birthDate)
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    /**
     * Get birthDate
     *
     * @return string
     */
    public function getBirthDate()
    {
        return $this->birthDate;
    }

    // gender
    /**
     * Set gender
     *
     * @param  string $gender
     * @return User
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    // cityId
    /**
     * Set cityId
     *
     * @param  string $cityId
     * @return User
     */
    public function setCityId($cityId)
    {
        $this->cityId = $cityId;

        return $this;
    }

    /**
     * Get cityId
     *
     * @return string
     */
    public function getCityId()
    {
        return $this->cityId;
    }

    // linkVK
    /**
     * Set linkVK
     *
     * @param  string $linkVK
     * @return User
     */
    public function setLinkVK($linkVK)
    {
        $this->linkVK = $linkVK;

        return $this;
    }

    /**
     * Get linkVK
     *
     * @return string
     */
    public function getLinkVK()
    {
        return $this->linkVK;
    }

    // linkFB
    /**
     * Set linkFB
     *
     * @param  string $linkFB
     * @return User
     */
    public function setLinkFB($linkFB)
    {
        $this->linkFB = $linkFB;

        return $this;
    }

    /**
     * Get linkFB
     *
     * @return string
     */
    public function getLinkFB()
    {
        return $this->linkFB;
    }

    // linkGP
    /**
     * Set linkGP
     *
     * @param  string $linkGP
     * @return User
     */
    public function setLinkGP($linkGP)
    {
        $this->linkGP = $linkGP;

        return $this;
    }

    /**
     * Get linkGP
     *
     * @return string
     */
    public function getLinkGP()
    {
        return $this->linkGP;
    }

    // autoProfilesLinks
    /**
     * Set autoProfilesLinks
     *
     * @param  string $autoProfilesLinks
     * @return User
     */
    public function setAutoProfilesLinks($autoProfilesLinks)
    {
        $this->autoProfilesLinks = $autoProfilesLinks;

        return $this;
    }

    /**
     * Get autoProfilesLinks
     *
     * @return string
     */
    public function getAutoProfilesLinks()
    {
        return $this->autoProfilesLinks;
    }

    // link_garage
    /**
     * Set link_garage
     *
     * @param  string $link_garage
     * @return User
     */
    public function setLinkGarage($link_garage)
    {
        $this->linkGarage = $link_garage;

        return $this;
    }

    /**
     * Get link_garage
     *
     * @return string
     */
    public function getLinkGarage()
    {
        return $this->linkGarage;
    }

    // contentGroupId
    /**
     * Set contentGroupId
     *
     * @param  string $contentGroupId
     * @return User
     */
    public function setContentGroupId($contentGroupId)
    {
        $this->contentGroupId = $contentGroupId;

        return $this;
    }

    /**
     * Get contentGroupId
     *
     * @return string
     */
    public function getContentGroupId()
    {
        return $this->contentGroupId;
    }

    // description
    /**
     * Set description
     *
     * @param  string $description
     * @return User
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

    // jobId
    /**
     * Set jobId
     *
     * @param  string $jobId
     * @return User
     */
    public function setJobId($jobId)
    {
        $this->jobId = $jobId;

        return $this;
    }

    /**
     * Get jobId
     *
     * @return string
     */
    public function getJobId()
    {
        return $this->jobId;
    }

    // subscriptions
    /**
     * Set subscriptions
     *
     * @param  string $subscriptions
     * @return User
     */
    public function setSubscriptions($subscriptions)
    {
        $this->subscriptions = $subscriptions;

        return $this;
    }

    /**
     * Get subscriptions
     *
     * @return string
     */
    public function getSubscriptions()
    {
        return $this->subscriptions;
    }

    // feedbacks
    /**
     * Set feedbacks
     *
     * @param  string $feedbacks
     * @return User
     */
    public function setFeedbacks($feedbacks)
    {
        $this->feedbacks = $feedbacks;

        return $this;
    }

    /**
     * Get feedbacks
     *
     * @return string
     */
    public function getFeedbacks()
    {
        return $this->feedbacks;
    }

    // requests
    /**
     * Set requests
     *
     * @param  string $requests
     * @return User
     */
    public function setRequests($requests)
    {
        $this->requests = $requests;

        return $this;
    }

    /**
     * Get requests
     *
     * @return string
     */
    public function getRequests()
    {
        return $this->requests;
    }

    public function getJob()
    {
        return $this->job;
    }

    public function setJob(Dictionary $job)
    {
        $this->job = $job;

        return $this;
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
}
