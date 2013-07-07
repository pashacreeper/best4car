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

        return $datetime->format('d.m.Y');
    }

    public function reverseTransform($timestamp)
    {
        $date = new \DateTime();
        $date->setTimestamp($timestamp);

        return $date;
    }
}
