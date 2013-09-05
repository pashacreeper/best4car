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

    /**
     * @ORM\Column(name="is_complain", type="boolean", nullable=true)
     */
    private $complain;

    /**
     * @ORM\Column(name="is_hidden", type="boolean", nullable=true)
     */
    private $hidden;

    public function __construct(Feedback $feedback = null)
    {
        $this->date = new \DateTime('now');

        if ($feedback) {
            $this->setFeedback($feedback);
        }
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
    public function getFeedbackId()
    {
        return $this->feedbackId;
    }
    /**
     * Get feedback
     */
    public function getFeedback()
    {
        return $this->feedback;
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

    public function __toString()
    {
        return "Ответ на отзыв #{$this->feedbackId}";
    }
}
