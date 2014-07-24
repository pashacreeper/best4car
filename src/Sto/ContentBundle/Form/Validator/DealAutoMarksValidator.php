<?php

namespace Sto\ContentBundle\Form\Validator;

use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraint;

class DealAutoMarksValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if ($value->isEmpty()) {
            $this->context->addViolation($constraint->message);
        }
    }
}
