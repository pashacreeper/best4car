<?php
namespace Sto\ContentBundle\Form\Extension\ChoiceList;

use Symfony\Component\Form\Extension\Core\ChoiceList\SimpleChoiceList;

class FeedbackSort extends SimpleChoiceList
{
    const RATING = "rating";
    const DATE = "date";

    public function __construct()
    {
        $choices = [
            self::RATING => self::RATING,
            self::DATE => self::DATE,
        ];
        parent::__construct($choices);
    }
}
