<?php
namespace Sto\ContentBundle\Form\Extension\ChoiceList;

use Symfony\Component\Form\Extension\Core\ChoiceList\SimpleChoiceList;

class FuelType extends SimpleChoiceList
{
    public function __construct()
    {
        $choices = [
            98 => 98,
            95 => 95,
            92 => 92,
            80 => 80,
            70 => 70,
        ];
        parent::__construct($choices);
    }
}
