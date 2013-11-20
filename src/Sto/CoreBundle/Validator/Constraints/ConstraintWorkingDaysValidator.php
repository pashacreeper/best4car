<?php

namespace Sto\CoreBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ConstraintWorkingDaysValidator extends ConstraintValidator
{
    /**
     * Checks if the passed value is valid.
     *
     * @param mixed      $value      The value that should be validated
     * @param Constraint $constraint The constraint for the validation
     *
     * @api
     */
    public function validate($value, Constraint $constraint)
    {
        if (!in_array(true, $value)) {
            $this->context->addViolation($constraint->message);
        }
    }
}
