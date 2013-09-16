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
            new \Twig_SimpleFilter('working_time_days_number_to_array', array($this, 'workingTimeDaysNumberToArray')),
            new \Twig_SimpleFilter('working_time_days_array_to_string', array($this, 'workingTimeDaysArrayToString')),
        );
    }

    public function urlToLocalFilter($url)
    {
        return str_replace(['https://', 'http://', $_SERVER['HTTP_HOST']], '', $url);
    }

    public function workingTimeDaysNumberToArray($days)
    {
        $response = [];
        foreach ([1, 2, 4, 8, 16, 32, 64] as $day) {
            $response[] = ($day & $days) > 0;
        }

        return $response;
    }

    public function workingTimeDaysArrayToString($days)
    {
        $dayLabels = ['Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб', 'Вс'];
        $chunks = [];

        foreach ($days as $k => $v) {
            if ($v) {
                $chunks[] = $dayLabels[$k];
            }
        }

        return implode(', ', $chunks);
    }
}
