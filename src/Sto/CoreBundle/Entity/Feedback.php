<?php

namespace Sto\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Sto\UserBundle\Entity\User;

/**
 * Feedback
 *
 * @ORM\Entity(repositoryClass="Sto\CoreBundle\Repository\FeedbackRepository")
 * @ORM\Table(name="feedbacks")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"company" = "FeedbackCompany", "deal" = "FeedbackDeal"})
 */
class Feedback
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @Assert\Length(
     *     min = "180",
     *     max = "8000"
     * )
     * @ORM\Column(name="content", type="text", nullable=true)
     */
    private $content;

    /**
     * @ORM\Column(name="visit_dates", type="date", nullable=true)
     */
    private $visitDate;

    /**
     * @ORM\Column(name="master_name", type="string", length=255, nullable=true)
     */
    private $mastername;

    /**
     * @ORM\Column(name="car", type="string", length=255, nullable=true)
     */
    private $car;

    /**
     * @ORM\Column(name="state_number", type="string", length=255, nullable=true)
     */
    private $stateNumber;

    /**
     * @ORM\Column(name="order_number", type="string", length=255, nullable=true)
     */
    private $orderNumber;

    /**
     * @ORM\Column(name="user_id", type="integer")
     */
    private $userId;

    /**
     * @ORM\ManyToOne(targetEntity="\Sto\UserBundle\Entity\User", inversedBy="feedbacks")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

     /**
     * @ORM\Column(name="feedback_rating", type="float")
     */
    private $feedbackRating;

    /**
     * @ORM\Column(name="pluses", type="integer")
     */
    private $pluses;

    /**
     * @ORM\Column(name="minuses", type="integer")
     */
    private $minuses;

    /**
     * @ORM\Column(name="target_rating", type="string", length=255, nullable=true)
     */
    private $targetRating;

    /**
     * @ORM\Column(name="published", type="boolean")
     */
    private $published;

    /**
     * @ORM\OneToOne(targetEntity="FeedbackAnswer", mappedBy="feedback", cascade={"remove"})
     */
    private $feedbackAnswer;

    /**
     * @ORM\Column(name="date_feedback", type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(name="ip", type="string", length=255, nullable=true)
     */
    private $ip;

    /**
     * @ORM\OneToMany(targetEntity="FeedbackEvaluation", mappedBy="feedback", cascade={"remove"})
     */
    private $evaluation;

    /**
     * @ORM\Column(name="is_complain", type="boolean", nullable=true)
     */
    private $complain;

    /**
     * @ORM\Column(name="is_hidden", type="boolean", nullable=true)
     */
    private $hidden;

    public function __construct(User $user = null)
    {
        if ($user) {
            $this->setUser($user);
        }

        $this->date = new \DateTime('now');
        $this->visitDate = new \DateTime('now');
        $this->hidden = false;
    }

   /**
     * Get time for edit
     */
    public function getMinutes()
    {
        if (!$this->date)
            return 0;
        $str = $this->date->format("d.m.Y.H.i.s");

        return (int) ((date('YmdHis') - date('YmdHis', strtotime($str)))/60);
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
        if ($user) {
            $this->user = $user;
            $this->userId = $user->getId();

            $user->addFeedbacks($this);
        }

        return $this;
    }

    /**
     * Get user
     *
     * @return \Sto\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    public function getUserId()
    {
        return $this->userId;

        return $this;
    }

    public function getPluses()
    {
        return $this->pluses;
    }

    public function setPluses($count)
    {
        $this->pluses = $count;

        return $this;
    }

    public function addPlus()
    {
        $this->pluses++;

        return $this;
    }

    public function subPlus()
    {
        $this->pluses--;
        $this->minuses++;

        return $this;
    }

    public function getMinuses()
    {
        return $this->minuses;
    }

    public function setMinuses($count)
    {
        $this->minuses = $count;

        return $this;
    }

    public function addMinus()
    {
        $this->minuses++;

        return $this;
    }

    public function subMinus()
    {
        $this->minuses--;
        $this->pluses++;

        return $this;
    }

    public function __toString()
    {
        return "Feedback from user #{$this->userId}";
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

    public function setDate(\DateTime $date)
    {
        $this->date = $date;

        return $this;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function setComplain($value)
    {
        $this->complain = $value;

        return $this;
    }

    public function isComplain()
    {
        return $this->complain;
    }

    public function setHidden($value)
    {
        $this->hidden = $value;

        return $this;
    }

    public function isHidden()
    {
        return $this->hidden;
    }

    /**
     * Set userId
     *
     * @param  integer  $userId
     * @return Feedback
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get published
     *
     * @return boolean
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * Get complain
     *
     * @return boolean
     */
    public function getComplain()
    {
        return $this->complain;
    }

    /**
     * Get hidden
     *
     * @return boolean
     */
    public function getHidden()
    {
        return $this->hidden;
    }

    /**
     * Add evaluation
     *
     * @param  \Sto\CoreBundle\Entity\FeedbackEvaluation $evaluation
     * @return Feedback
     */
    public function addEvaluation(\Sto\CoreBundle\Entity\FeedbackEvaluation $evaluation)
    {
        $this->evaluation[] = $evaluation;

        return $this;
    }

    /**
     * Remove evaluation
     *
     * @param \Sto\CoreBundle\Entity\FeedbackEvaluation $evaluation
     */
    public function removeEvaluation(\Sto\CoreBundle\Entity\FeedbackEvaluation $evaluation)
    {
        $this->evaluation->removeElement($evaluation);
    }

    /**
     * Get evaluation
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEvaluation()
    {
        return $this->evaluation;
    }
}
