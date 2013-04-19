<?php

namespace Sto\UserBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity,
    Symfony\Component\HttpFoundation\File\File,
    Symfony\Component\HttpFoundation\File\UploadedFile,
    Symfony\Component\Validator\Constraints as Assert;
use Sto\CoreBundle\Entity\Dictionary,
    Sto\CoreBundle\Entity\Feedback,
    Sto\UserBundle\Entity\Group,
    Sto\UserBundle\Entity\RatingGroup;
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
     * @Assert\Image(
     *     maxSize="2M",
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
     * @Assert\Choice(callback = "getGenders")
     * @ORM\Column(name="gender", type="string", nullable=true)
     */
    protected $gender;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", nullable=true)
     */
    protected $city;

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
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="subscriptions", type="string", length=255, nullable=true)
     */
    private $subscriptions;

    /**
     * @ORM\OneToMany(targetEntity="Sto\CoreBundle\Entity\Feedback", mappedBy="user", cascade={"all"})
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

    /**
     * @ORM\ManyToMany(targetEntity="Sto\CoreBundle\Entity\Company", mappedBy="managers")
     */
    private $companies;

    public function __construct()
    {
        parent::__construct();
        $this->gender = 'male';
        $this->feedbacks = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public static function getGenders()
    {
        return ['male', 'female'];
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

    /**
     * Set city
     *
     * @param  string $city
     * @return User
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
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
}
