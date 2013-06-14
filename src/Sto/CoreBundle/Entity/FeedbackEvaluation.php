<?php

namespace Sto\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sto\UserBundle\Entity\User;

/**
 * FeedbackEvaluation
 *
 * @ORM\Table(name="feedback_evaluations")
 * @ORM\Entity
 */
class FeedbackEvaluation
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Feedback", inversedBy="evaluation", cascade={"persist"})
     * @ORM\JoinColumn(name="feedback_id", referencedColumnName="id")
     */
    private $feedback;

    /**
     * @ORM\ManyToOne(targetEntity="Sto\UserBundle\Entity\User", inversedBy="evaluation")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\Column(name="review", type="boolean")
     */
    private $review;

    public function __construct($review, User $user = null, Feedback $feedback = null)
    {
        if ($user) {
            $this->setUser($user);
        }

        if ($feedback) {
            $this->setFeedback($feedback);
        }

        $this->setReview($review);
    }

    /**
     * Get id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set review
     */
    public function setReview($review)
    {
        $this->review = $review;

        return $this;
    }

    /**
     * Get review
     */
    public function getReview()
    {
        return $this->review;
    }

    /**
     * Set user
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set feedback
     */
    public function setFeedback(Feedback $feedback = null)
    {
        $this->feedback = $feedback;

        return $this;
    }

    /**
     * Get user
     */
    public function getFeedback()
    {
        return $this->feedback;
    }
}
