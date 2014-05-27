<?php

namespace Sto\ContentBundle\Controller;

use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Class SubscriptionController
 *
 * @package Sto\ContentBundle\Controller
 *
 * @Route("subscriptions")
 */
class SubscriptionController extends Controller
{
    /**
     * @Template
     * @Route("/", name="subscription_list")
     * @Secure("ROLE_USER")
     */
    public function indexAction()
    {
        $user = $this->getUser();

        return compact('user');
    }
}
