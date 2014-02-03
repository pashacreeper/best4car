<?php

namespace Sto\CoreBundle\Service\Base;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormFactoryInterface;

/**
 * Class Controller
 *
 * @package Sto\CoreBundle\Service\Base
 */
class Controller
{
    /** @var \Doctrine\Common\Persistence\ObjectManager */
    private $em;

    /** @var \Symfony\Component\Form\FormFactoryInterface */
    private $formFactory;

    /**
     * @param ObjectManager                                $em
     * @param \Symfony\Component\Form\FormFactoryInterface $formFactory
     */
    public function __construct(ObjectManager $em, FormFactoryInterface $formFactory)
    {
        $this->em = $em;
        $this->formFactory = $formFactory;
    }

    /**
     * @param       $type
     * @param null  $data
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createForm($type, $data = null, array $options = array())
    {
        return $this->formFactory->create($type, $data, $options);
    }
}
