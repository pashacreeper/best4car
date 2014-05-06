<?php
namespace Sto\ContentBundle\Form\Extension\ChoiceList;

use Symfony\Component\Form\Extension\Core\ChoiceList\SimpleChoiceList;

class EngineType extends SimpleChoiceList
{
    const PETROL = "petrol";
    const DIESEL = "diesel";

    public function __construct()
    {
        $choices = [
            self::PETROL => "Бензиновый",
            self::DIESEL => "Дизельный",
        ];
        parent::__construct($choices);
    }
}
