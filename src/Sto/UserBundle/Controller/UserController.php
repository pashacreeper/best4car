<?php

namespace Sto\UserBundle\Controller;

use Doctrine\ORM\EntityManager;
use FOS\UserBundle\Model\UserInterface;
use Sto\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\UserBundle\Controller\SecurityController as SecurityController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Router;

/**
 * Description of UserController
 */
class UserController extends SecurityController
{
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

    /**
     * @Template()
     */
    public function userLoginAction()
    {
        $csrfToken = $this->container->get('form.csrf_provider')->generateCsrfToken('authenticate');

        return [
            'csrf_token' => $csrfToken,
        ];
    }

    /**
     * @Route("/unsubscribe-vk", name="vk_unsubscribe_account")
     */
    public function unsubscribeVkAction()
    {
        /** @var $user User */
        $user = $this->container->get('security.context')->getToken()->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        if ($user->hasVkontakteAccessToken()) {
            $user->setVkontakteAccessToken(null);
        }

        if ($user->hasVkontakteId()) {
            $user->setVkontakteId(null);
        }

        /** @var $em EntityManager */
        $em = $this->container->get('doctrine.orm.entity_manager');
        $em->persist($user);
        $em->flush();

        /** @var $router Router */
        $router = $this->container->get('router');

        return new RedirectResponse(
            $router->generate('fos_user_profile_edit')
        );
    }
}
