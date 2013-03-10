<?php

namespace Sto\ContentBundle\Builder;

use Symfony\Component\HttpFoundation\Request,
    Symfony\Component\Security\Core\SecurityContext,
    Symfony\Component\Routing\Router,
    Symfony\Component\Translation\Translator;
use FOS\UserBundle\Model\UserInterface;
use Mopa\Bundle\BootstrapBundle\Navbar\AbstractNavbarMenuBuilder;

class MenuBuilder extends AbstractNavbarMenuBuilder
{
    protected $securityContext;

    protected $router;

    public function createMainMenu(Request $request, Translator $translator)
    {
        $menu = $this->createNavbarMenuItem();
        $context = $this->securityContext;

        $menu->addChild('Компании', [
            'route' => 'content_companies',
            'extras' => [],
        ]);
        $menu->addChild('Акции', [
            'route' => 'content_deals',
            'extras' => [],
        ]);
        $menu->addChild('Клубы', [
            'route' => 'content_clubs',
            'extras' => [],
        ]);
        $menu->addChild('Эксперты', [
            'route' => 'content_experts',
            'extras' => [],
        ]);

        return $menu;
    }

    public function createRightSideDropdownMenu(Request $request, Translator $translator)
    {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav pull-right');

        $user = $this->securityContext->getToken()->getUser();

        if (is_object($user) && $user instanceof UserInterface) {
            $profile = $this->createDropdownMenuItem($menu, $translator->trans('menu.welcome') . ", " . $user->getUsername(), false, ['caret' => true]);
            $profile->addChild('Account info', [
                'route' => 'fos_user_profile_show',
                'extras' => [
                    'icon' => 'info-sign',
                ],
            ]);
            $profile->addChild('Account info', [
                'route' => 'fos_user_profile_show',
                'extras' => [
                    'icon' => 'info-sign',
                ],
            ]);
            $this->addDivider($profile);
            $profile->addChild($translator->trans('menu.logout'), [
                'route' => 'fos_user_security_logout',
                'extras' => [
                    'icon' => 'off',
                ],
            ]);
        } else {
            $menu->addChild('Войти', [
                'route' => 'fos_user_security_login',
                'extras' => [],
            ]);
        }

        return $menu;
    }

    public function setSecurityContext(SecurityContext $context)
    {
        $this->securityContext = $context;
    }

    public function setRouter(Router $router)
    {
        $this->router = $router;
    }
}
