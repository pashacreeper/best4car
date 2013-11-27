<?php

namespace Sto\CoreBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class ConstraintWorkingTime extends Constraint
{
    public $message = "Необходимо выбрать хотя бы один рабочий день и указать для него рабочие часы";
}
