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

    public static function getOptions() {
        return [
            self::COMPANY => 'компании',
            self::DEAL => 'акции',
        ];
    }

    public static function getTypeAndClass()
    {
        return [
            self::COMPANY => '\Sto\ContentBundle\Form\Type\CompanySubscriptionType',
            self::DEAL => '\Sto\ContentBundle\Form\Type\DealSubscriptionType',
        ];
    }
}
