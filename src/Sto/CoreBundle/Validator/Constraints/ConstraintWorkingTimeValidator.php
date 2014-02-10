<?php

namespace Sto\CoreBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ConstraintWorkingTimeValidator extends ConstraintValidator
{
    /**
     * Checks if the passed value is valid.
     *
     * @param mixed      $value      The value that should be validated
     * @param Constraint $constraint The constraint for the validation
     */
    public function validate($value, Constraint $constraint)
    {
        $wrongTime = false;
        foreach ($value as $item) {
            if (!in_array(true, $item->getDays()) || !$item->getFromTime() || !$item->getTillTime()) {
                $this->context->addViolation($constraint->message);
            }

            if ($item->getFromTime() > $item->getTillTime()) {
                $wrongTime = true;
            }
        }

        if($wrongTime) {
            $this->context->addViolation($constraint->timeMessage);
        }
    }
}
