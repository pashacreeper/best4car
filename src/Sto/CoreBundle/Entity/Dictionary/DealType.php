<?php

namespace Sto\CoreBundle\Entity\Dictionary;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Deal
 *
 * @ORM\Entity()
 */
class DealType extends Base
{
    /**
     * @ORM\OneToMany(targetEntity="\Sto\CoreBundle\Entity\Deal", mappedBy="type", cascade={"persist"})
     */
    protected $deals;

    public function __construct()
    {
        parent::__construct();
        $this->deals = new ArrayCollection();
    }

    public function addDeal(\Sto\CoreBundle\Entity\Deal $deal)
    {
        $this->deals[] = $deal;

        return $this;
    }

    public function removeDeal(\Sto\CoreBundle\Entity\Deal $deal)
    {
        $this->deals->removeElement($deal);

        return $this;
    }

    public function getDeals()
    {
        return $this->deals->toArray();
    }
}
