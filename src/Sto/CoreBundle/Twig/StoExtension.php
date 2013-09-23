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
            new \Twig_SimpleFilter('url_to_local', [$this, 'urlToLocalFilter']),
            new \Twig_SimpleFilter('urlFix', [$this, 'urlFix']),
            new \Twig_SimpleFilter('working_time_days_to_string', [$this, 'workingTimeDaysArrayToString']),
        );
    }

    public function urlToLocalFilter($url)
    {
        return str_replace(['https://', 'http://', $_SERVER['HTTP_HOST']], '', $url);
    }

    public function workingTimeDaysArrayToString($days)
    {
        $labels = ['Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб', 'Вс'];
        $result = '';

        foreach ($days as $key => $value) {
            if (!$value) {
                continue;
            }
            $previousKey = $key - 1;
            $nextKey = $key + 1;
            if (!isset($days[$previousKey]) || !$days[$previousKey]) {
                if (!empty($result)) {
                    $result .= ', ';
                }
                $result .= $labels[$key];
            } elseif (isset($days[$nextKey]) && !$days[$nextKey]) {
                $result .= ' - ' . $labels[$key];
            } elseif (!isset($days[$nextKey])) {
                $result .= ' - ' . $labels[$key];
            }
        }

        return $result;
    }

    public function urlFix($url)
    {
        if (!preg_match('/(http|https):\/\//', $url)) {
            $url = 'http://' . $url;
        }

        return $url;
    }
}
