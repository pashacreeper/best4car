<?php
namespace Sto\ContentBundle\Form\Extension\ChoiceList;

use Symfony\Component\Form\Extension\Core\ChoiceList\SimpleChoiceList;

class CompanyRegistrationStep extends SimpleChoiceList
{
    const BASE = "base";
    const BUSINESS = "business";
    const CONTACTS = "contacts";
    const GALLERY = "gallery";

    public function __construct()
    {
        $choices = [
            self::BASE => self::BASE,
            self::BUSINESS => self::BUSINESS,
            self::CONTACTS => self::CONTACTS,
            self::GALLERY => self::GALLERY,
        ];
        parent::__construct($choices);
    }
}