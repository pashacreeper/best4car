<?php

namespace Sto\ContentBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

class Builder extends ContainerAware
{
    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $route = $this->container->get('request')->get('_route');
        $routeParameters = $this->container->get('request')->get('_route_params');
        if (isset($routeParameters['name'])) {
            $routeParametersName = $routeParameters['name'];
        }
        $menu = $factory->createItem('root');
        $menu->setChildrenAttributes(['class' => 'navTop']);

        $companies = $menu->addChild('Компании', ['route' => '_index']);
        $companies->setAttribute('class', 'navTopItem');
        $companies->setLinkAttributes(['data-span-class' => 'companys', 'class' => 'navLink']);

        if (in_array($route, [
            'content_company_feedbacks_add',
            '_index',
            'content_company_show',
            'company_deal_arhive'
        ])) {
            $companies->setCurrent(true);
        }

        $deals = $menu->addChild('Акции', ['route' => 'content_deals']);
        $deals->setAttribute('class', 'navTopItem');
        $deals->setLinkAttributes(['data-span-class' => 'actions', 'class' => 'navLink']);
        if (in_array($route, [
            'content_deal_show',
            'content_deals',
        ])) {
            $deals->setCurrent(true);
        }

        $clubs = $menu->addChild('Клубы', ['route' => 'info_show', 'routeParameters' => ['name' => 'clubs']]);
        $clubs->setAttribute('class', 'navTopItem');
        $clubs->setLinkAttributes(['data-span-class' => 'clubs', 'class' => 'navLink']);
        if ('info_show' == $route && 'clubs' == $routeParametersName) {
            $clubs->setCurrent(true);
        }

        $experts = $menu->addChild('Эксперты', ['route' => 'info_show', 'routeParameters' => ['name' => 'experts']]);
        $experts->setAttribute('class', 'navTopItem');
        $experts->setLinkAttributes(['data-span-class' => 'experts', 'class' => 'navLink']);
        if (in_array($route, [
            'user_profile',
            'fos_user_profile_show',
            'fos_user_profile_edit',
        ]) || ('info_show' == $route && 'experts' == $routeParametersName)) {
            $experts->setCurrent(true);
        }

        return $menu;
    }

    public function footerMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');
        $menu->setChildrenAttributes(['class' => 'footerMenu']);

        $page = $menu->addChild('О проекте', ['route' => 'info_show', 'routeParameters' => ['name' => 'about']]);
        $page->setAttribute('class', 'footerMenuItem');

        $page = $menu->addChild('Для автобизнеса', ['route' => 'info_show', 'routeParameters' => ['name' => 'business']]);
        $page->setAttribute('class', 'footerMenuItem');

        $page = $menu->addChild('Тур по сайту', ['uri' => '#']);
        $page->setAttribute('class', 'footerMenuItem');
        $page->setLinkAttribute('data-reveal-id', 'for-car-owners');

        $page = $menu->addChild('Контакты', ['route' => 'info_show', 'routeParameters' => ['name' => 'contact']]);
        $page->setAttribute('class', 'footerMenuItem');

        return $menu;
    }

    public function footerSocial(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');
        $menu->setChildrenAttributes(['class' => 'footerCocial']);

        $link = $menu->addChild('Вконтакте', ['uri' => 'http://vk.com/best4car']);
        $link->setAttribute('class', 'footerCocialItem');
        $link->setLinkAttributes(['class' => 'vk']);

        return $menu;
    }

}
