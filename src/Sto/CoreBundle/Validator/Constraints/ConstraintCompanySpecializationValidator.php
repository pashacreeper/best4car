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
            if (! $value->first() instanceof CompanySpecialization) {
                $this->context->addViolation($constraint->message);
            }
        }
    }
}