<?php

namespace Sto\CoreBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ConstrainIsImageValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        foreach ($value as $item) {
            if ($item->getId() === null && ! $item->getImage() instanceof UploadedFile) {
                $this->context->addViolation($constraint->message);
            }
        }
    }
}
