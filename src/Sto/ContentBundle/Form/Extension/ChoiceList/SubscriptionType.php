<?php

namespace Sto\ContentBundle\Form\Extension\ChoiceList;

use Symfony\Component\Form\Extension\Core\ChoiceList\SimpleChoiceList;

class SubscriptionType extends SimpleChoiceList
{
    const COMPANY = 'company';
    const DEAL = 'deal';

    public function __construct()
    {
        $choices = [
            self::COMPANY => self::COMPANY,
            self::DEAL    => self::DEAL
        ];
        parent::__construct($choices);
    }
}
