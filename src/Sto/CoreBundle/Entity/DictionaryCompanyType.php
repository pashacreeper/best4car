<?php

namespace Sto\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DictionaryCompanyType
 *
 * @ORM\Entity()
 */
class DictionaryCompanyType extends Dictionary
{
    /**
     * @ORM\ManyToMany(targetEntity="Company")
     */
    private $companies;
}
