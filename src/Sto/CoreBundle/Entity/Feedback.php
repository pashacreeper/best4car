<?php

namespace Sto\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Feedback
 *
 * @ORM\Table(name="feedbacks")
 * @ORM\Entity(repositoryClass="Sto\CoreBundle\Repository\FeedbackRepository")
 */
class Feedback
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
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @ORM\Column(name="visit_dates", type="date")
     */
    private $visitDate;

    /**
     * @var string
     * @ORM\Column(name="master_name", type="string", length=255, nullable=true)
     */
    private $mastername;

    /**
     * @var string
     * @ORM\Column(name="car", type="string", length=255, nullable=true)
     */
    private $car;

    /**
     * @var string
     * @ORM\Column(name="state_number", type="string", length=255, nullable=true)
     */
    private $stateNumber; // Гос номер автомобиля

    /**
     * @var string
     * @ORM\Column(name="order_number", type="string", length=255, nullable=true)
     */
    private $orderNumber; // Номер заказ-наряда

    /**
     * @var integer
     * @ORM\Column(name="user_id", type="integer")
     */
    private $userId;

    /**
     * @ORM\ManyToOne(targetEntity="\Sto\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @var integer
     * @ORM\Column(name="company_rating", type="integer")
     */
    private $companyRating;

     /**
     * @var float
     * @ORM\Column(name="feedback_rating", type="float")
     */
    private $feedbackRating;

    /**
     * @var string
     * @ORM\Column(name="pluses", type="string")
     */
    private $pluses;

    /**
     * @var string
     * @ORM\Column(name="minuses", type="string")
     */
    private $minuses;

    /**
     * @var string
     *
     * @ORM\Column(name="target_rating", type="string", length=255, nullable=true)
     */
    private $targetRating;  // Что оцениваем

    /**
     * @var boolean
     *
     * @ORM\Column(name="published", type="boolean")
     */
    private $published;  // Публикация

    /**
     * @ORM\OneToOne(targetEntity="FeedbackAnswer", mappedBy="feedback", cascade={"remove"})
     */
    private $feedbackAnswer;

    /**
     *
     * @var string
     *
     * @ORM\Column(name="ip", type="string", length=255, nullable=true)
     */
    private $ip;

    /**
     *
     * @var interger
     * @ORM\Column(name="currency_level_id", type="integer", nullable=true)
     */
    private $currencyLevelId;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Dictionary")
     * @ORM\JoinColumn(name="currency_level_id", referencedColumnName="id")
     */
    private $currencyLevel;

    /**
     *
     * @var integer
     * @ORM\Column(name="company_id", type="integer", nullable=true)
     */
    private $companyId;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Company", inversedBy="feedbacks")
     * @ORM\JoinColumn(name="company_id", referencedColumnName="id")
     */
    private $company;

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
     * Set content
     *
     * @param  string   $content
     * @return Feedback
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set visitDate
     *
     * @param  \DateTime $visitDate
     * @return Feedback
     */
    public function setVisitDate($visitDate)
    {
        $this->visitDate = $visitDate;

        return $this;
    }

    /**
     * Get visitDate
     *
     * @return \DateTime
     */
    public function getVisitDate()
    {
        return $this->visitDate;
    }

    /**
     * Set mastername
     *
     * @param  string   $mastername
     * @return Feedback
     */
    public function setMastername($mastername)
    {
        $this->mastername = $mastername;

        return $this;
    }

    /**
     * Get mastername
     *
     * @return string
     */
    public function getMastername()
    {
        return $this->mastername;
    }

    /**
     * Set car
     *
     * @param  string   $car
     * @return Feedback
     */
    public function setCar($car)
    {
        $this->car = $car;

        return $this;
    }

    /**
     * Get car
     *
     * @return string
     */
    public function getCar()
    {
        return $this->car;
    }

    /**
     * Set stateNumber
     *
     * @param  string   $stateNumber
     * @return Feedback
     */
    public function setStateNumber($stateNumber)
    {
        $this->stateNumber = $stateNumber;

        return $this;
    }

    /**
     * Get stateNumber
     *
     * @return string
     */
    public function getStateNumber()
    {
        return $this->stateNumber;
    }

    /**
     * Set orderNumber
     *
     * @param  string   $orderNumber
     * @return Feedback
     */
    public function setOrderNumber($orderNumber)
    {
        $this->orderNumber = $orderNumber;

        return $this;
    }

    /**
     * Get orderNumber
     *
     * @return string
     */
    public function getOrderNumber()
    {
        return $this->orderNumber;
    }

    /**
     * Set comapnyRating
     *
     * @param  integer  $comapnyRating
     * @return Feedback
     */
    public function setCompanyRating($companyRating)
    {
        $this->companyRating = $companyRating;

        return $this;
    }

    /**
     * Get comapnyRating
     *
     * @return integer
     */
    public function getCompanyRating()
    {
        return $this->companyRating;
    }

    /**
     * Set feedbackRating
     *
     * @param  integer  $feedbackRating
     * @return Feedback
     */
    public function setFeedbackRating($feedbackRating)
    {
        $this->feedbackRating = $feedbackRating;

        return $this;
    }

    /**
     * Get feedbackRating
     *
     * @return integer
     */
    public function getFeedbackRating()
    {
        return $this->feedbackRating;
    }

    /**
     * Set targetRating
     *
     * @param  string   $targetRating
     * @return Feedback
     */
    public function setTargetRating($targetRating)
    {
        $this->targetRating = $targetRating;

        return $this;
    }

    /**
     * Get targetRating
     *
     * @return string
     */
    public function getTargetRating()
    {
        return $this->targetRating;
    }

    /**
     * Set isPublished
     *
     * @param  boolean  $isPublished
     * @return Feedback
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * Get isPublished
     *
     * @return boolean
     */
    public function isPublished()
    {
        return $this->published;
    }

    /**
     * Set user
     *
     * @param  \Sto\UserBundle\Entity\User $user
     * @return Feedback
     */
    public function setUser(\Sto\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Sto\UserBundle\Emtity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function getPluses()
    {
        return $this->pluses;
    }

    public function setPluses($count)
    {
        $this->pluses = $count;
    }

    public function getMinuses()
    {
        return $this->minuses;
    }

    public function setMinuses($count)
    {
        $this->minuses = $count;
    }

    public function __toString()
    {
        return $this->getContent();
    }

    /**
     * Add answer
     */
    public function setFeedbackAnswer(FeedbackAnswer $answer)
    {
        $this->feedbackAnswer = $answer;

        return $this;
    }

    /**
     * Get answers
     */
    public function getFeedbackAnswer()
    {
        return $this->feedbackAnswer;
    }

    public function getIp()
    {
        return $this->ip;
    }

    public function setIp($ip_address)
    {
        $this->ip = $ip_address;

        return $this;
    }

    public function getCurrencyLevel()
    {
        return $this->currencyLevel;
    }

    public function setCurrencyLevel(Dictionary $level)
    {
        $this->currencyLevel = $level;

        return $this;
    }

    public function getCompany()
    {
        return $this->company;
    }

    public function setCompany(Company $company)
    {
        $this->company = $company;

        return $this;
    }
}
