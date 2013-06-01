<?php

namespace Sto\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FeedbackEvaluation
 *
 * @ORM\Table(name="feedback_evaluations")
 * @ORM\Entity
 */
class FeedbackEvaluation
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
     * @var integer
     *
     * @ORM\Column(name="review", type="boolean")
     */
    private $review;

    public function __construct(
        \Sto\UserBundle\Entity\User $user = null,
        \Sto\CoreBundle\Entity\Feedback $feedback = null,
        $review = true)
    {
        if ($user) {
            $this->setUser($user);
        }
        if ($feedback) {
            $this->setFeedback($feedback);
        }
        if ($review) {
            $this->setReview($review);
        }
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
     * Set review
     *
     * @param  boolean            $review
     * @return FeedbackEvaluation
     */
    public function setReview($review)
    {
        $this->review = $review;

        return $this;
    }

    /**
     * Get review
     *
     * @return boolean
     */
    public function getReview()
    {
        return $this->review;
    }

    /**
     * Set user
     *
     * @param  \Sto\UserBundle\Entity\User $user
     * @return FeedbackEvaluation
     */
    public function setUser(\Sto\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Sto\UserBundle\Entity\User $user
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set feedback
     *
     * @param  \Sto\CoreBundle\Entity\Feedback $feedback
     * @return FeedbackEvaluation
     */
    public function setFeedback(\Sto\CoreBundle\Entity\Feedback $feedback = null)
    {
        $this->feedback = $feedback;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Sto\CoreBundle\Entity\Feedback $feedback
     */
    public function getFeedback()
    {
        return $this->feedback;
    }
}
