<?php

namespace Sto\CoreBundle\Entity\Dictionary;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * Work
 *
 * @ORM\Entity()
 */
class Work extends Base
{
    /**
     * @ORM\Column(type="string", length=255, name="description", nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToMany(targetEntity="Sto\CoreBundle\Entity\Deal")
     * @Serializer\Exclude
     */
    private $deals;

    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->deals = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add deals
     *
     * @param  \Sto\CoreBundle\Entity\Deal $deals
     * @return Work
     */
    public function addDeal(\Sto\CoreBundle\Entity\Deal $deals)
    {
        $this->deals[] = $deals;

        return $this;
    }

    /**
     * Remove deals
     *
     * @param \Sto\CoreBundle\Entity\Deal $deals
     */
    public function removeDeal(\Sto\CoreBundle\Entity\Deal $deals)
    {
        $this->deals->removeElement($deals);
    }

    /**
     * Get deals
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDeals()
    {
        return $this->deals;
    }
}
