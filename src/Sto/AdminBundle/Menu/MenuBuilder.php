<?php

namespace Sto\AdminBundle\Menu;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Routing\Router;
use Symfony\Component\Translation\Translator;
use FOS\UserBundle\Model\UserInterface;
use Mopa\Bundle\BootstrapBundle\Navbar\AbstractNavbarMenuBuilder;


use Sto\UserBundle\Entity\User;

class MenuBuilder extends AbstractNavbarMenuBuilder
{
    protected $securityContext;

    protected $router;

    public function createMainMenu(Request $request, Translator $translator)
    {

        $menu = $this->createNavbarMenuItem();
        $context = $this->securityContext;
        $menu->addChild($translator->trans('menu.home'), array(
            'route' => 'admin_index',
            'extras' => array(
                'icon' => 'home',
            ),
        ));

        if ($context->isGranted('ROLE_ADMIN')) {
            $requestTracking = $this->createDropdownMenuItem($menu, $translator->trans('menu.menu_name.lists'), true, array('icon' => 'caret'));
            $requestTracking->addChild($translator->trans('menu.menu_name.dict'), array('route' => 'dictionary'));
            $requestTracking->addChild($translator->trans('menu.menu_name.countries'), array('route' => 'country'));
            $requestTracking->addChild($translator->trans('menu.menu_name.cities'), array('route' => 'city'));
        }


        if ($context->isGranted('ROLE_ADMIN')) {
            $systemManagement = $this->createDropdownMenuItem($menu, "System Management", true, array('icon' => 'caret'));
            $systemManagement->addChild('Manage Users', array(
                'route' => 'admin_index',
                'extras' => array(
                    'icon' => 'user',
                ),
            ));
            $systemManagement->addChild('Manage News', array(
                'route' => 'root',
                'extras' => array(
                    'icon' => 'envelope',
                ),
            ));
            $systemManagement->addChild('Manage Forms', array(
                'route' => 'root',
                'extras' => array(
                    'icon' => 'list-alt',
                ),
            ));
        }

        if ($context->isGranted('ROLE_ADMIN')) {
            $systemManagement->addChild('Manage Companies', array(
                'route' => 'companies',
                'extras' => array(
                    'icon' => 'th-list',
                ),
            ));
        }

        if ($context->isGranted('ROLE_ADMIN')) {
            $this->addDivider($systemManagement);
            $systemManagement->addChild('Load Data Files', array(
                'route' => 'root',
                'extras' => array(
                    'icon' => 'upload',
                ),
            ));
        }

        return $menu;
    }

    public function createRightSideDropdownMenu(Request $request, Translator $translator)
    {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav pull-right');

        $user = $this->securityContext->getToken()->getUser();
        if (is_object($user) && $user instanceof UserInterface) {
            $profile = $this->createDropdownMenuItem($menu, $translator->trans('menu.welcome') . ", " . $user->getUsername(), false, array('icon' => 'caret'));
            $profile->addChild(' Account info', array(
                'route' => 'root',
                'extras' => array(
                    'icon' => 'info-sign',
                ),
            ));
            $this->addDivider($profile);
            $profile->addChild($translator->trans('menu.logout'), array(
                'route' => 'fos_user_security_logout',
                'extras' => array(
                    'icon' => 'off',
                ),
            ));

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
