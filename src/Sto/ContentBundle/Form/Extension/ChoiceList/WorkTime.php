<?php

namespace Sto\ContentBundle\Form\Extension\ChoiceList;

use Symfony\Component\Form\Extension\Core\ChoiceList\SimpleChoiceList;

class WorkTime extends SimpleChoiceList
{
    const AROUND_THE_CLOCK = "around_the_clock";
    const LATE = "late";
    const WEEKENDS = "weekends";

    public function __construct()
    {
        $choices = [
            self::AROUND_THE_CLOCK => self::AROUND_THE_CLOCK,
            self::LATE => self::LATE,
            self::WEEKENDS => self::WEEKENDS
        ];
        parent::__construct($choices);
    }
}
