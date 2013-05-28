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
}
