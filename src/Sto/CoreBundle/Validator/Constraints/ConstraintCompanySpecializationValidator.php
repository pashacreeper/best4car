<?php

namespace Sto\CoreBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Sto\CoreBundle\Entity\CompanySpecialization;
use Doctrine\Common\Collections\ArrayCollection;

class ConstraintCompanySpecializationValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (! $value instanceof ArrayCollection) {
            if (! $value->first() instanceof CompanySpecialization 
                || ($value->first()->getType() === null && $value->first()->getSubType() === null)
            ) {
                $this->context->addViolationAt('specializations', $constraint->message);
            }
        }
    }
}
