<?php

namespace Sto\CoreBundle\Twig;

class StoExtension extends \Twig_Extension
{
    public function getName()
    {
        return 'sto_extension';
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('url_to_local', array($this, 'urlToLocalFilter')),
        );
    }

    public function urlToLocalFilter($url)
    {
        return str_replace(['https://', 'http://', $_SERVER['HTTP_HOST']], '', $url);
    }
}