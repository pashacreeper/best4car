<?php

namespace Sto\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\UserBundle\Controller\SecurityController as SecurityController;

use Sto\UserBundle\Entity\User;

/**
 * Description of UserController
 *
 */
class UserController extends SecurityController {

	/**
	 * @Route("/login-form", name="login_form")
	 */
    public function LoginBisAction()
    {
        $csrfToken = $this->container->get('form.csrf_provider')->generateCsrfToken('authenticate');

        return $this->container->get('templating')->renderResponse('FOSUserBundle:Security:login_content.html.twig', array(
            'last_username' => null,
            'error'         => null,
            'csrf_token'    => $csrfToken
        ));
    }
}