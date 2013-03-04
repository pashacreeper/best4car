<?php

namespace Sto\AdminBundle\Menu;

use Symfony\Component\HttpFoundation\Request,
    Symfony\Component\Security\Core\SecurityContext,
    Symfony\Component\Routing\Router,
    Symfony\Component\Translation\Translator;
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
            $menu->addChild($translator->trans('menu.menu_name.lists'), array('route' => 'dictionary'));
        }

        if ($context->isGranted('ROLE_ADMIN')) {
            $systemManagement = $this->createDropdownMenuItem($menu, "System Management", true, array('caret' => true));
            $systemManagement->addChild('Manage Users', array(
                'route' => 'admin_user',
                'extras' => array(
                    'icon' => 'user',
                ),
            ));
            $systemManagement->addChild('Manage Contant Groups', array(
                'route' => 'group_list',
                'extras' => array(
                    'icon' => 'group',
                ),
            ));
            $systemManagement->addChild('Manage Rating Groups', array(
                'route' => 'rating_groups',
                'extras' => array(
                    'icon' => 'rating_group',
                ),
            ));
            $systemManagement->addChild('Manage Feedback', array(
                'route' => 'feedbacks',
                'extras' => array(
                    'icon' => 'envelope',
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
            $systemManagement->addChild('Manage Deals', array(
                'route' => 'deals',
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
            $profile = $this->createDropdownMenuItem($menu, $translator->trans('menu.welcome') . ", " . $user->getUsername(), false, array('caret' => true));
            $profile->addChild(' Account info', array(
                'route' => 'fos_user_profile_show',
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
