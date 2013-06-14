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
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="answer", type="text")
     */
    private $answer;

    /**
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(name="owner_id", type="integer")
     */
    private $ownerId;

    /**
     * @ORM\ManyToOne(targetEntity="\Sto\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="owner_id", referencedColumnName="id")
     */
    private $owner;

    /**
     * @ORM\Column(name="feedback_id", type="integer")
     */
    private $feedbackId;

    /**
     * @ORM\OneToOne(targetEntity="Feedback", inversedBy="feedbackAnswer")
     * @ORM\JoinColumn(name="feedback_id", referencedColumnName="id")
     */
    private $feedback;

    public function __construct(Feedback $feedback = null)
    {
        $this->date = new \DateTime('now');

        if ($feedback) {
            $this->setFeedback($feedback);
        }
    }

    /**
     * Get id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set answer
     */
    public function setAnswer($answer)
    {
        $this->answer = $answer;

        return $this;
    }

    /**
     * Get answer
     */
    public function getAnswer()
    {
        return $this->answer;
    }

    /**
     * Set date
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set owner
     */
    public function setOwner(\Sto\UserBundle\Entity\User $owner = null)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get owner
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Set feedback
     */
    public function setFeedback(Feedback $feedback)
    {
        $this->feedback = $feedback;
        $this->feedbackId = $feedback->getId();
        $feedback->setFeedbackAnswer($this);

        return $this;
    }

    /**
     * Get feedback
     */
    public function getFeedback()
    {
        return $this->feedback;
    }
}
