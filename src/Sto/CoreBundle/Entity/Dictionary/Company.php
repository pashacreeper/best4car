<?php

namespace Sto\CoreBundle\Entity\Dictionary;

use Doctrine\ORM\Mapping as ORM;

/**
 * Company
 *
 * @ORM\Entity()
 */
class Company extends Base
{
    /**
     * @ORM\ManyToMany(targetEntity="Company")
     */
    private $companies;
}
