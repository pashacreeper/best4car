<?php

namespace Sto\ContentBundle\Builder;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Routing\Router;
use Symfony\Component\Translation\Translator;
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

        return $menu;
    }

    public function createRightSideDropdownMenu(Request $request, Translator $translator)
    {
        $menu = $this->factory->createItem('root');
        $context = $this->securityContext;
        $menu->setChildrenAttribute('class', 'nav pull-right');

        $menu->addChild('Ваш город', [
            'uri' => '#',
            'extras' => [],
            'attributes' => ['id' => 'choice-city'],
        ]);

        $user = $context->getToken()->getUser();

        if (is_object($user) && $user instanceof UserInterface) {
            $companies = $user->getCompanies();
            $profile = $this->createDropdownMenuItem($menu, $translator->trans('menu.welcome') . ", " . $user->getUsername(), false, ['caret' => true]);
            $profile->addChild('Профиль', [
                'route' => 'fos_user_profile_show',
                'extras' => [
                    'icon' => 'info-sign',
                ],
            ]);
            if ($context->isGranted('ROLE_ADMIN')) {
                $profile->addChild('Панель администратора', [
                    'route' => 'admin_index',
                    'extras' => [
                        'icon' => 'info-sign',
                    ],
                ]);
            }
            $this->addDivider($profile);
            if ($context->isGranted('ROLE_MANAGER')) {
                foreach ($companies as $key => $company) {
                    $profile->addChild((($key+1)).'.Компания "'.$company->getName().'"', [
                        'uri' => '#',
                        'extras' => [],
                    ]);
                    $profile->addChild(($key+1).'-Информация' , [
                        'route' => 'content_company_show_tab',
                        'routeParameters'=> [
                            'id'=>$company->getId(),
                            'tab'=>'information'
                            ],
                        'extras' => [
                            'icon' => 'info-signs',
                        ],
                    ]);
                    $profile->addChild(($key+1).'-Отзывы' , [
                        'route' => 'content_company_show_tab',
                        'routeParameters'=> [
                            'id'=>$company->getId(),
                            'tab'=>'feetbacks'
                            ],
                        'extras' => [
                            'icon' => 'info-sign',
                        ],
                    ]);
                    $profile->addChild(($key+1).'-Акции' , [
                        'route' => 'content_company_show_tab',
                        'routeParameters'=> [
                            'id'=>$company->getId(),
                            'tab'=>'deals'
                            ],
                        'extras' => [
                            'icon' => 'info-sign',
                        ],
                    ]);
                 $this->addDivider($profile);
                }

            }


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
            $menu->addChild('Регистрация', [
                'route' => 'fos_user_registration_register',
                'extras' => [],
                'attributes' => [
                    'id' => 'registration-link',
                    'data-modal' => 'registrationModal',
                ],
            ]);
        }

        return $menu;
    }

    public function createBottomMenu()
    {
        $menu = $this->createNavbarMenuItem();

        $menu->addChild('О проекте', [
            'uri' => '#',
            'extras' => [],
        ]);
        $menu->addChild('Рекламодателям', [
            'uri' => '#',
            'extras' => [],
        ]);
        $menu->addChild('Помощь', [
            'uri' => '#',
            'extras' => [],
        ]);
        $menu->addChild('Тур', [
            'uri' => '#',
            'extras' => [],
        ]);
        $menu->addChild('Обратная связь', [
            'uri' => '#',
            'extras' => [],
        ]);

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
