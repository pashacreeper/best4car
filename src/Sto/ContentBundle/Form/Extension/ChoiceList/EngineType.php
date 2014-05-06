<?php
namespace Sto\ContentBundle\Form\Extension\ChoiceList;

use Symfony\Component\Form\Extension\Core\ChoiceList\SimpleChoiceList;

class EngineType extends SimpleChoiceList
{
    const PETROL = "petrol";
    const DIESEL = "diesel";

    public function __construct()
    {
        parent::__construct(self::getOptions());
    }

    public static function getOptions() {
        return [
            self::PETROL => "Бензиновый",
            self::DIESEL => "Дизельный",
        ];
    }
}
