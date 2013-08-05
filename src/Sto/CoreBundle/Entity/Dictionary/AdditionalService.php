<?php

namespace Sto\CoreBundle\Entity\Dictionary;

use Doctrine\ORM\Mapping as ORM;

/**
 * AdditionalService
 *
 * @ORM\Entity()
 */
class AdditionalService extends Base
{
    /**
     * @ORM\ManyToMany(targetEntity="\Sto\CoreBundle\Entity\Company")
     */
    private $companies;
}
