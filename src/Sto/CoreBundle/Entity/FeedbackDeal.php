<?php

namespace Sto\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FeedbackDeal
 *
 * @ORM\Entity()
 */
class FeedbackDeal extends Feedback
{
    /**
     * @ORM\Column(name="deal_rating", type="integer")
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

    /**
     * Set dealRating
     *
     * @param  integer  $dealRating
     * @return Feedback
     */
    public function setDealRating($dealRating)
    {
        $this->dealRating = $dealRating;

        return $this;
    }

    /**
     * Get dealRating
     *
     * @return integer
     */
    public function getDealRating()
    {
        return $this->dealRating;
    }

    public function getDeal()
    {
        return $this->deal;
    }

    public function setDeal(Deal $deal)
    {
        $this->deal = $deal;

        return $this;
    }
}
