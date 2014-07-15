<?php
namespace Sto\ContentBundle\Form\Extension\ChoiceList;

use Symfony\Component\Form\Extension\Core\ChoiceList\SimpleChoiceList;

class BodyType extends SimpleChoiceList
{
    const SEDAN = "sedan";
    const HATCHBACK = "hatchback";
    const UNIVERSAL = "universal";

    public function __construct()
    {
        parent::__construct(self::getOptions());
    }

    public static function getOptions()
    {
        return [
            self::SEDAN => "Седан",
            self::HATCHBACK => "Хетчбек",
            self::UNIVERSAL => "Универсал",
        ];
    }
}
