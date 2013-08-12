<?php
namespace Sto\ContentBundle\Form\Extension\ChoiceList;

use Symfony\Component\Form\Extension\Core\ChoiceList\SimpleChoiceList;

class CompaniesSort extends SimpleChoiceList
{
    const RATING = "rating";
    const PRICE = "price";
    const REMOTENESS = "remoteness";

    public function __construct()
    {
        $choices = [
            self::RATING => self::RATING,
            self::PRICE => self::PRICE,
            self::REMOTENESS => self::REMOTENESS,
        ];
        parent::__construct($choices);
    }
}
