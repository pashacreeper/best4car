<?php

namespace Sto\ContentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sto\ContentBundle\Controller\ChoiceCityController as MainController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/info")
 */

class PageController extends MainController
{

    /**
     * @Route("/{name}", name="info_show")
     * @Template()
     */
    public function showAction($name)
    {
        return [];
    }

    /**
     * @Route("/contact", name="info_contact")
     * @Template()
     */
    public function contactAction()
    {
        return [];
    }

}
