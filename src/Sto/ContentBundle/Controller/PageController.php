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
     * @Route("/useRules", name="info_rules_2_use")
     * @Template()
     */
    public function useRulesAction()
    {
        return [];
    }

    /**
     * @Route("/about", name="info_about")
     * @Template()
     */
    public function aboutAction()
    {
        return [];
    }

    /**
     * @Route("/advertisers", name="info_advertisers")
     * @Template()
     */
    public function advertisersAction()
    {
        return [];
    }

    /**
     * @Route("/business", name="info_auto_business")
     * @Template()
     */
    public function autoBusinessAction()
    {
        return [];
    }

    /**
     * @Route("/tour", name="info_tour")
     * @Template()
     */
    public function tourAction()
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
