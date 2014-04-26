<?php
namespace Sto\ContentBundle\Form\Extension\ChoiceList;

use Symfony\Component\Form\Extension\Core\ChoiceList\SimpleChoiceList;

class TransmissionType extends SimpleChoiceList
{
    const MT = "mt";
    const AT = "at";
    const MTA = "mta";
    const CVT = "cvt";
    const HSD = "hsd";

    public function __construct()
    {
        $choices = [
            self::MT => strtoupper(self::MT),
            self::AT => strtoupper(self::AT),
            self::MTA => strtoupper(self::MTA),
            self::CVT => strtoupper(self::CVT),
            self::HSD => strtoupper(self::HSD),
        ];
        parent::__construct($choices);
    }
}
