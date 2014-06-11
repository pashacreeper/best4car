<?php
namespace Sto\ContentBundle\Form\Extension\ChoiceList;

use Symfony\Component\Form\Extension\Core\ChoiceList\SimpleChoiceList;

class WheelType extends SimpleChoiceList
{
    const FRONT = "front";
    const BACK = "back";
    const FULL = "full";

    public function __construct()
    {
        parent::__construct(self::getOptions());
    }

    public static function getOptions()
    {
        return [
            self::FRONT => "Передний",
            self::BACK => "Задний",
            self::FULL => "Полный",
        ];
    }
}
