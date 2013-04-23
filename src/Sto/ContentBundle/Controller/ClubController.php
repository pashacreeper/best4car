<?php

namespace Sto\ContentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class ClubController extends Controller
{
    /**
     * @Route("/clubs", name="content_clubs")
     * @Template()
     */
    public function indexAction()
    {
        return [];
    }
}
