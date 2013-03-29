<?php

namespace Sto\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * DictionaryCompanyType
 *
 * @ORM\Entity()
 */
class DictionaryDealsType extends Dictionary
{
    /**
     * @ORM\OneToMany(targetEntity="Deal", mappedBy="type", cascade={"persist"})
     */
    protected $deals;

    public function __construct()
    {
        parent::__construct();
        $this->deals = new ArrayCollection();
    }

    public function addDeal(Deal $deal)
    {
        $this->deals[] = $deal;

        return $this;
    }

    public function removeDeal(Deal $deal)
    {
        $this->deals->removeElement($deal);

        return $this;
    }

    public function getDeals()
    {
        return $this->deals->toArray();
    }
}
