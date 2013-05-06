<?php

namespace Sto\CoreBundle\Entity\Catalog;

use Doctrine\ORM\Mapping as ORM;

/**
 * Model
 *
 * @ORM\Entity()
 */
class Model extends Base
{
    public function __toString()
    {
        return $this->getParent() . " - " . $this->getName();
    }
}
