<?php
namespace Sto\ContentBundle\Form\Extension\ChoiceList;

use Symfony\Component\Form\Extension\Core\ChoiceList\SimpleChoiceList;

class AdditionalServices extends SimpleChoiceList
{
    const EVAQUATE = "evaquate";
    const WIFI = "wifi";
    const WAITING = "waiting";
    const COFFEE = "coffee";
    const RESTAURANT = "restaurant";
    const CREDIT_CARD = "credit_card";

    public function __construct()
    {
        $choices = [
            self::EVAQUATE => self::EVAQUATE,
            self::WIFI => self::WIFI,
            self::WAITING => self::WAITING,
            self::COFFEE => self::COFFEE,
            self::RESTAURANT => self::RESTAURANT,
            self::CREDIT_CARD => self::CREDIT_CARD
        ];
        parent::__construct($choices);
    }
}
