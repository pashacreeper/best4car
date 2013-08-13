<?php
namespace Sto\ContentBundle\Form\Extension\ChoiceList;

use Symfony\Component\Form\Extension\Core\ChoiceList\SimpleChoiceList;

class CompaniesSort extends SimpleChoiceList
{
    const RATING = "rating";
    const PRICE = "price";

    public function __construct()
    {
        $choices = [
            self::RATING => self::RATING,
            self::PRICE => self::PRICE,
        ];
        parent::__construct($choices);
    }
}
