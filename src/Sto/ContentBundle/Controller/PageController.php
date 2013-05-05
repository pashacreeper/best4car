<?php

namespace Sto\ContentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller,
    Symfony\Component\HttpFoundation\Request,
    Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Method,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Template,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Route("/info")
 */

class PageController extends Controller
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
     * @Route("/help", name="info_help")
     * @Template()
     */
    public function helpAction()
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

    /**
     * @Route("/advertisers", name="info_advertisers")
     * @Template()
     */
    public function advertisersAction()
    {
        return [];
    }

    /**
     * @Route("/tur", name="info_tur")
     * @Template()
     */
    public function turAction()
    {
        return [];
    }
}
