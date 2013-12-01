<?php

namespace Sto\CoreBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class ConstraintPhones extends Constraint
{
    public $message = "Необходимо указать телефон и описание для него";
}
