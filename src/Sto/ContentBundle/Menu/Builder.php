<?php

namespace Sto\ContentBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

class Builder extends ContainerAware
{
    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');
        $menu->setChildrenAttributes(['class' => 'navTop']);

        $companies = $menu->addChild('Компании', ['route' => '_index']);
        $companies->setAttribute('class', 'navTopItem');
        $companies->setLinkAttributes(['data-span-class' => 'companys', 'class' => 'navLink']);
        $companies->setLinkAttribute('class', 'navLink');

        $deals = $menu->addChild('Акции', ['route' => 'content_deals']);
        $deals->setAttribute('class', 'navTopItem');
        $deals->setLinkAttributes(['data-span-class' => 'actions', 'class' => 'navLink']);

        $clubs = $menu->addChild('Клубы', ['uri' => '#']);
        $clubs->setAttribute('class', 'navTopItem');
        $clubs->setLinkAttributes(['data-span-class' => 'clubs', 'class' => 'navLink']);

        $experts = $menu->addChild('Эксперты', ['uri' => '#']);
        $experts->setAttribute('class', 'navTopItem');
        $experts->setLinkAttributes(['data-span-class' => 'experts', 'class' => 'navLink']);

        return $menu;
    }

    public function footerMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');
        $menu->setChildrenAttributes(['class' => 'footerMenu']);

        $pages = [
            'about' => 'О проекте',
            'advertisers' => 'Для рекламодателей',
            'business' => 'Для автобизнеса',
            'tour' => 'Тур по сайту',
            'contact' => 'Контакты',
        ];

        foreach($pages as $key => $pageName) {
            $page = $menu->addChild($pageName, ['route' => 'info_show', 'routeParameters' => ['name' => $key]]);
            $page->setAttribute('class', 'footerMenuItem');
        }

        $contacts = $menu->addChild('Контакты', ['route' => 'info_contact']);
        $contacts->setAttribute('class', 'footerMenuItem');

        return $menu;
    }

    public function footerSocial(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');
        $menu->setChildrenAttributes(['class' => 'footerCocial']);

        $items = [
            'google' => 'Google+',
            'facebook' => 'Facebook',
            'vk' => 'Вконтакте',
        ];

        foreach($items as $key => $value) {
            $link = $menu->addChild($value, ['uri' => '#']);
            $link->setAttribute('class', 'footerCocialItem');
            $link->setLinkAttributes(['class' => $key]);
        }

        return $menu;
    }

}
