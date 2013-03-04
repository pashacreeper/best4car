<?php

namespace Sto\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FeedbackAnswer
 *
 * @ORM\Table(name="feedback_answers")
 * @ORM\Entity(repositoryClass="Sto\CoreBundle\Repository\FeedbackAnswerRepository")
 */
class FeedbackAnswer
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
     * @ORM\Column(name="answer", type="text")
     */
    private $answer;

    /**
     * @var string
     * @ORM\Column(name="date", type="date")
     */
    private $date;

    /**
     * @var integer
     * @ORM\Column(name="owner_id", type="integer")
     */
    private $ownerId;

    /**
     * @ORM\ManyToOne(targetEntity="\Sto\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="owner_id", referencedColumnName="id")
     */
    private $owner;

    /**
     * @var integer
     * @ORM\Column(name="feedback_id", type="integer")
     */
    private $feedbackId;

    /**
     * @ORM\OneToOne(targetEntity="Feedback", inversedBy="feedbackAnswer")
     * @ORM\JoinColumn(name="feedback_id", referencedColumnName="id")
     */
    private $feedback;

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
     * Set answer
     *
     * @param  string         $answer
     * @return FeedbackAnswer
     */
    public function setAnswer($answer)
    {
        $this->answer = $answer;

        return $this;
    }

    /**
     * Get answer
     *
     * @return string
     */
    public function getAnswer()
    {
        return $this->answer;
    }

    /**
     * Set date
     *
     * @param  \DateTime      $date
     * @return FeedbackAnswer
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set owner
     *
     * @param  \Sto\UserBundle\Entity\User $owner
     * @return FeedbackAnswer
     */
    public function setOwner(\Sto\UserBundle\Entity\User $owner = null)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get owner
     *
     * @return \Sto\UserBundle\Entity\User
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Set feedback
     *
     * @param  \Sto\CoreBundle\Entity\Feedback $feedback
     * @return Feedback
     */
    public function setFeedback(Feedback $feedback = null)
    {
        $this->feedback = $feedback;
        $this->feedbackId = $feedback->getId();
        $feedback->setFeedbackAnswer($this);

        return $this;
    }

    /**
     * Get feedback
     *
     * @return \Sto\CoreBundle\Entity\Feedback
     */
    public function getFeedback()
    {
        return $this->feedback;
    }
}
