<?php

namespace Sto\UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Sto\CoreBundle\Entity\CompanyManager;
use Sto\CoreBundle\Entity\Feedback;
use Sto\UserBundle\Entity\Group;
use Sto\UserBundle\Entity\RatingGroup;
use Sto\UserBundle\Entity\UserContacts;
use Sto\UserBundle\Entity\UserGallery;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="Sto\UserBundle\Entity\Repository\UserRepository")
 * @ORM\Table(name="users")
 * @Vich\Uploadable
 * @UniqueEntity("username")
 * @UniqueEntity("email")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=255)
     */
    protected $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=255, nullable=true)
     *
     */
    protected $lastName;

    /**
     * @var integer
     *
     * @ORM\Column(name="rating", type="integer", nullable=true)
     */
    protected $rating;

    /**
     * @var integer
     *
     * @ORM\Column(name="rating_bonus", type="integer", nullable=true)
     */
    protected $ratingBonus;

    /**
     * @var integer
     *
     * @ORM\Column(name="rating_group_id", type="integer", nullable=true)
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
     * @Assert\Image(
     *     maxSize="5M",
     *     mimeTypes={"image/png", "image/jpeg", "image/pjpeg"}
     * )
     * @Vich\UploadableField(mapping="user_photo", fileNameProperty="avatarUrl")
     */
    protected $avatar;

    /**
     * @var string $avatarUrl
     *
     * @ORM\Column(name="avatar_url", type="string", length=255, nullable=true)
     */
    protected $avatarUrl;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birth_date", type="date", nullable=true)
     */
    private $birthDate;

    /**
     * @ORM\Column(name="gender", type="string", nullable=true)
     */
    protected $gender;

    /**
     * @ORM\ManyToOne(targetEntity="\Sto\CoreBundle\Entity\Country", inversedBy="users")
     * @ORM\JoinColumn(name="city_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="city_id", type="integer", nullable=true)
     */
    private $cityId;

    /**
     * @var string
     *
     * @ORM\Column(name="link_vk", type="string", length=255, nullable=true)
     */
    private $linkVK;

    /**
     * @var string
     *
     * @ORM\Column(name="vk_id", type="string", length=255, nullable=true)
     */
    private $vkontakteId;

    /**
     * @var string
     *
     * @ORM\Column(name="vk_access_token", type="string", length=255, nullable=true)
     */
    private $vkontakteAccessToken;

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
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="Sto\CoreBundle\Entity\Feedback", mappedBy="user", cascade={"all"})
     */
    private $feedbacks;

    /**
     * @ORM\OneToMany(targetEntity="Sto\CoreBundle\Entity\FeedbackAnswer", mappedBy="owner", cascade={"all"})
     */
    private $feedbackAnswers;

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

    /**
     * @ORM\OneToMany(targetEntity="Sto\UserBundle\Entity\UserContacts", mappedBy="user", cascade={"all"})
     */
    private $contacts;

    /**
     * @ORM\OneToMany(targetEntity="Sto\CoreBundle\Entity\CompanyManager", mappedBy="user", cascade={"all"})
     */
    private $companyManager;

    /**
     * @ORM\OneToMany(targetEntity="Sto\CoreBundle\Entity\FeedbackEvaluation", mappedBy="user", cascade={"all"})
     */
    private $evaluation;

    /**
     * @ORM\Column(name="using_email", type="boolean")
     */
    private $usingEmail;

    /**
     * @var string
     *
     * @ORM\Column(name="avatar_vk", type="string", length=255, nullable=true)
     */
    private $avatarVk;

    /**
     * @ORM\OneToMany(targetEntity="UserGallery", mappedBy="user", cascade={"all"})
     */
    private $gallery;

    /**
     * @var string
     *
     * @ORM\Column(name="contact_email", type="string", length=255, nullable=true)
     */
    private $contactEmail;

    /**
     * @ORM\OneToMany(targetEntity="UserCar", mappedBy="user", cascade={"all"})
     */
    private $cars;

    /**
     * @ORM\OneToMany(targetEntity="Sto\CoreBundle\Entity\Subscription", mappedBy="user")
     */
    protected $subscriptions;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="feed_view_at", type="datetime", nullable=true)
     */
    private $feedViewAt;

    /**
     * @ORM\Column(name="feed_notify", type="boolean")
     */
    private $feedNotify = false;

    public function __construct()
    {
        parent::__construct();
        $this->gender = 'male';
        $this->feedbacks = new ArrayCollection();
        $this->ratingGroupId = 1;
        $this->groups = new ArrayCollection();
        $this->enabled = true;
        $this->contacts = new ArrayCollection();
        $this->companyManager = new ArrayCollection();
        $this->rating = 10;
        $this->usingEmail = true;
        $this->gallery = new ArrayCollection();
        $this->cars = new ArrayCollection();
        $this->subscriptions = new ArrayCollection();
    }

    public function __toString()
    {
        return (string) $this->username;
    }

    public static function getGenders()
    {
        return ['male', 'female'];
    }

    public static function getAllRoles()
    {
        return [
            'ROLE_ADMIN' => 'Администратор',
            'ROLE_MODERATOR' => 'Модератор',
            'ROLE_EDITOR' => 'Редактор',
            'ROLE_MANAGER' => 'Менеджер',
            'ROLE_USER' => 'Пользователь'
        ];
    }

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

    /**
     * Set bonus rating
     *
     * @param  string $ratingBonus
     * @return User
     */
    public function setRatingBonus($ratingBonus)
    {
        $this->ratingBonus = $ratingBonus;

        return $this;
    }

    /**
     * Get rating bonus
     *
     * @return string
     */
    public function getRatingBonus()
    {
        return $this->ratingBonus;
    }

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

    public function setCity(\Sto\CoreBundle\Entity\Country $city)
    {
        $this->city = $city;
        $this->cityId = $city->getId();
        $city->addUser($this);

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

    /**
     * Set vkId
     *
     * @param  string $linkVK
     * @return User
     */
    public function setVkontakteId($vkId)
    {
        $this->vkontakteId = $vkId;

        return $this;
    }

    /**
     * Get vkId
     *
     * @return string
     */
    public function getVkontakteId()
    {
        return $this->vkontakteId;
    }

    public function setVkontakteAccessToken($token)
    {
        $this->vkontakteAccessToken = $token;

        return $this;
    }

    public function getVkontakteAccessToken()
    {
        return $this->vkontakteAccessToken;
    }

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

    /**
     * Get autoProfilesLinkCount
     *
     */
    public function getAutoProfilesLinksCount()
    {
        $arr = strtok($this->autoProfilesLinks," ,;\t\n");

        return count($arr);
    }

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
     * Get feedbacks
     */
    public function getFeedbacks()
    {
        return $this->feedbacks;
    }

    public function getFeedbackAnswers()
    {
        return $this->feedbackAnswers;
    }

    /**
     * Get Years Old
     */
    public function getYears()
    {
        if (!$this->birthDate)
            return 0;
        $str = $this->birthDate->format("d.m.Y");

        return (int) ((date('Ymd') - date('Ymd', strtotime($str))) / 10000);
    }

    public function getContacts()
    {
        return $this->contacts;
    }

    public function addContact(UserContacts $contact)
    {
        $contact->setUser($this);
        $this->contacts[] = $contact;

        return $this;
    }

    public function removeContact(UserContacts $contact)
    {
        $this->contacts->removeElement($contact);
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

    public function isUsingEmail()
    {
        return $this->usingEmail;
    }

    public function setUsingEmail($val)
    {
        $this->usingEmail = $val;

        return $this;
    }

    public function setAvatarVk($avatar)
    {
        $this->avatarVk = $avatar;

        return $this;
    }

    public function getAvatarVk()
    {
        return $this->avatarVk;
    }

    /**
     * Add gallery
     */
    public function addGallery(UserGallery $gallery)
    {
        $this->gallery[] = $gallery;

        return $this;
    }

    /**
     * Remove gallery
     */
    public function removeGallery(UserGallery $gallery)
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

    public function getImagePath()
    {
        return $this->avatarUrl == null ? "/bundles/stocore/images/notimage.png" : "/storage/images/user_photo/{$this->avatarUrl}";
    }

    public function hasVkontakteAccessToken()
    {
        if ($this->getVkontakteAccessToken() !== null) {
            return true;
        } else {
            return false;
        }
    }

    public function hasVkontakteId()
    {
        if ($this->getVkontakteId() !== null) {
            return true;
        } else {
            return false;
        }
    }

    public function getContactEmail()
    {
        return $this->contactEmail;
    }

    public function setContactEmail($email)
    {
        $this->contactEmail = $email;

        return $this;
    }

    public function getCars()
    {
        return $this->cars;
    }

    /**
     * @return mixed
     */
    public function getSubscriptions()
    {
        return $this->subscriptions;
    }

    /**
     * @param mixed $subscriptions
     */
    public function setSubscriptions($subscriptions)
    {
        $this->subscriptions = $subscriptions;
    }

    /**
     * @return \DateTime
     */
    public function getFeedViewAt()
    {
        return $this->feedViewAt;
    }

    /**
     * @param \DateTime $feedViewAt
     */
    public function setFeedViewAt($feedViewAt)
    {
        $this->feedViewAt = $feedViewAt;
    }

    /**
     * @return mixed
     */
    public function getFeedNotify()
    {
        return $this->feedNotify;
    }

    /**
     * @param mixed $feedNotify
     */
    public function setFeedNotify($feedNotify)
    {
        $this->feedNotify = $feedNotify;
    }

    public function getCars()
    {
        return $this->cars;
    }
}
