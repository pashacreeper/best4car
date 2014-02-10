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

        if ($wrongTime) {
            $this->context->addViolation($constraint->timeMessage);
        }

        if ($this->isTimeCollision($value)) {
            $this->context->addViolation($constraint->timeCollisionMessage);
        }
    }

    private function isTimeCollision($value)
    {
        foreach ($value as $item) {
            if ($this->findColliding($value, $item)) {
                return true;
            }
        }

        return false;
    }

    private function findColliding($value, $checkItem)
    {
        foreach ($value as $item) {
            if ($item === $checkItem) {
                continue;
            }

            foreach ($item->getDays() as $key => $day) {
                if ($day && $checkItem->getDays()[$key]) {
                    if ($this->isTimeColliding($item, $checkItem)) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    private function isTimeColliding($item, $otherItem)
    {
        // ...a1...a...b...b1.. - item overlap
        if ($item->getFromTime() < $otherItem->getFromTime() && $item->getTillTime() > $otherItem->getTillTime()) {
            return true;
        }

        // ...a...a1...b...b1.. - from time inside
        if ($item->getFromTime() > $otherItem->getFromTime() && $item->getFromTime() < $otherItem->getTillTime()) {
            return true;
        }

        // ...a...a1...b...b1.. - till time inside
        if ($item->getTillTime() > $otherItem->getFromTime() && $item->getTillTime() < $otherItem->getTillTime()) {
            return true;
        }

        return false;
    }
}
