<?php
namespace Sto\ContentBundle\Form\Extension\ChoiceList;

use Symfony\Component\Form\Extension\Core\ChoiceList\SimpleChoiceList;

class FeedbackFilter extends SimpleChoiceList
{
    const ALL = "all";
    const POSITIVE = "positive";
    const NEGATIVE = "negative";
    const USEFUL = "useful";

    public function __construct()
    {
        $choices = [
            self::ALL => self::ALL,
            self::POSITIVE => self::POSITIVE,
            self::NEGATIVE => self::NEGATIVE,
            self::USEFUL => self::USEFUL,
        ];
        parent::__construct($choices);
    }
}
