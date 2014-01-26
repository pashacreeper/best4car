<?php

namespace Sto\CoreBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class ConstraintEmails extends Constraint
{
    public $message = "Необходимо указать email и описание для него";
}
