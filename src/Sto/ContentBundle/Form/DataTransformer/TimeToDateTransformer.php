<?php

namespace Sto\ContentBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

class TimeToDateTransformer implements DataTransformerInterface
{
    public function transform($datetime)
    {
        if (!$datetime) {
            return null;
        }

        return $datetime->format('H:i');
    }

    public function reverseTransform($time)
    {
        list($hours, $minutes) = explode(":", $time, 2);
        $date = new \DateTime();
        $date->setTime($hours, $minutes);

        return $date;
    }
}
