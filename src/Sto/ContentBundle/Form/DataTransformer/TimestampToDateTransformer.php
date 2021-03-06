<?php

namespace Sto\ContentBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

class TimestampToDateTransformer implements DataTransformerInterface
{
    public function transform($datetime)
    {
        if (!$datetime) {
            return null;
        }

        return $datetime->format('Y-m-d');
    }

    public function reverseTransform($date)
    {
        list($year, $month, $day) = explode("-", $date, 3);
        $date = new \DateTime();
        $date->setDate($year, $month, $day);

        return $date;
    }
}
