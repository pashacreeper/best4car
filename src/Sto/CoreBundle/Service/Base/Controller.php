<?php

namespace Sto\CoreBundle\Service\Base;

use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class Controller
 *
 * @package Sto\CoreBundle\Service\Base
 */
class Controller
{
    /**
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    private $em;

    /**
     * @param ObjectManager $em
     */
    public function __construct(ObjectManager $em)
    {
        $this->em = $em;
    }
}
