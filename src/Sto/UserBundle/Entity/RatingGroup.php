<?php

namespace Sto\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FeedbackAnswer
 *
 * @ORM\Table(name="rating_group")
 * @ORM\Entity(repositoryClass="Sto\UserBundle\Repository\RatingGroupRepository")
 */
class RatingGroup
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
     * @ORM\Column(name="name", type="string")
     */
    private $name;

    /**
     * @var integer
     * @ORM\Column(name="min_rating", type="integer")
     */
    private $minRating;

    /**
     * @var integer
     * @ORM\Column(name="max_rating", type="integer")
     */
    private $maxRating;

    /**
     * @ORM\OneToMany(targetEntity="Sto\UserBundle\Entity\User", mappedBy="ratingGroup")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity="Sto\CoreBundle\Entity\Company", mappedBy="ratingGroup")
     * @ORM\JoinColumn(name="company_id", referencedColumnName="id")
     */
    private $companies;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @param  string      $name
     * @return RatingGroup
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
     * Set minRating
     *
     * @param  integer     $minRating
     * @return RatingGroup
     */
    public function setMinRating($minRating)
    {
        $this->minRating = $minRating;

        return $this;
    }

    /**
     * Get minRating
     *
     * @return integer
     */
    public function getMinRating()
    {
        return $this->minRating;
    }

    /**
     * Set maxRating
     *
     * @param  integer     $maxRating
     * @return RatingGroup
     */
    public function setMaxRating($maxRating)
    {
        $this->maxRating = $maxRating;

        return $this;
    }

    /**
     * Get maxRating
     *
     * @return integer
     */
    public function getMaxRating()
    {
        return $this->maxRating;
    }

    /**
     * Set userId
     *
     * @param  integer     $userId
     * @return RatingGroup
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Add user
     *
     * @param  \Sto\UserBundle\Entity\User $user
     * @return RatingGroup
     */
    public function addUser(\Sto\UserBundle\Entity\User $user)
    {
        $this->user[] = $user;

        return $this;
    }

    /**
     * Remove user
     *
     * @param \Sto\UserBundle\Entity\User $user
     */
    public function removeUser(\Sto\UserBundle\Entity\User $user)
    {
        $this->user->removeElement($user);
    }

    /**
     * Get user
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Get user
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCompanies()
    {
        return $this->companies;
    }

    public function __toString()
    {
        return $this->getName();
    }
}
