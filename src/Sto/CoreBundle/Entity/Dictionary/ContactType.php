<?php

namespace Sto\CoreBundle\Entity\Dictionary;

use Doctrine\ORM\Mapping as ORM;

/**
 * Contacts Type
 *
 * @ORM\Entity()
 */
class ContactType extends Base
{
    /**
     * @ORM\Column(name="prefix", type="string", length=255, nullable=true)
     */
    private $prefix;

    public function getPrefix()
    {
        return $this->prefix;
    }

    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;

        return $this;
    }
}
