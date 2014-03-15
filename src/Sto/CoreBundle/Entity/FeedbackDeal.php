<?php

namespace Sto\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sto\UserBundle\Entity\User;

/**
 * FeedbackDeal
 *
 * @ORM\Entity()
 */
class FeedbackDeal extends Feedback
{
    /**
     * @ORM\Column(name="deal_rating", type="integer", nullable=true)
     */
    private $dealRating;

    /**
     * @ORM\Column(name="deal_id", type="integer", nullable=true)
     */
    private $dealId;

    /**
     * @ORM\ManyToOne(targetEntity="Deal", inversedBy="feedbacks")
     * @ORM\JoinColumn(name="deal_id", referencedColumnName="id")
     */
    private $deal;

    public function __construct(User $user = null, Deal $deal = null)
    {
        parent::__construct($user);

        if ($deal) {
            $this->setDeal($deal);
        }
    }

    /**
     * Set dealRating
     */
    public function setDealRating($dealRating)
    {
        $this->dealRating = $dealRating;

        return $this;
    }

    /**
     * Get dealRating
     */
    public function getDealRating()
    {
        return $this->dealRating;
    }

    public function getDealId()
    {
        return $this->dealId;
    }

    public function setDealId($dealId)
    {
        $this->dealId = $dealId;

        return $this;
    }

    public function getDeal()
    {
        return $this->deal;
    }

    public function setDeal(Deal $deal)
    {
        $this->deal = $deal;
        $this->dealId = $deal->getId();

        return $this;
    }

    public function getFeedbackType()
    {
        return 'deal';
    }
}
