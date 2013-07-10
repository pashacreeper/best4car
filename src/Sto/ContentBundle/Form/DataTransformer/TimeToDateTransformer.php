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

        return $datetime->format('h:i:s');
    }

    public function reverseTransform($time)
    {
        list($hours, $minutes, $seconds) = explode(":", $time, 3);
        $date = new \DateTime();
        $date->setTime($hours, $minutes, $seconds);

        return $date;
    }
}
