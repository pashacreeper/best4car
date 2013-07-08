<?php

namespace Sto\ContentBundle\Controller;

use Sto\ContentBundle\Controller\ChoiceCityController as MainController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/info")
 */

class PageController extends MainController
{

    protected $pages = [
        'about' => 'О проекте',
        'advertisers' => 'Для рекламодателей',
        'business' => 'Для автобизнеса',
        'tour' => 'Тур по сайту',
        'contact' => 'Контакты',
        'useRules' => 'Условия использования сайта',
    ];

    /**
     * @Route("/{name}", name="info_show")
     */
    public function showAction($name)
    {
        if (! in_array($name, $this->pages)) {
            $this->createNotFoundException();
        }
        return $this->render('StoContentBundle:Page:' . $name . '.html.twig', ['title' => $this->pages[$name]]);
    }

}
