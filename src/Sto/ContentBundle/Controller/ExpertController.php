<?php

namespace Sto\ContentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class ExpertController extends Controller
{
    /**
     * @Route("/experts", name="content_experts")
     * @Template()
     */
    public function indexAction()
    {
        return [
        ];
    }
}
