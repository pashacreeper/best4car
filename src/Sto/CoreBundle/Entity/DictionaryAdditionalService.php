<?php

namespace Sto\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DictionaryAdditionalService
 *
 * @ORM\Entity()
 */
class DictionaryAdditionalService extends Dictionary
{
    /**
     * @ORM\ManyToMany(targetEntity="Company")
     */
    private $companies;
}
