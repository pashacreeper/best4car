<?php
namespace Sto\CoreBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\ORM\EntityManager;
use Sto\UserBundle\Entity\User;

class CompanyManagerValidator extends ConstraintValidator
{
    private $em;

    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    public function validate($value, Constraint $constraint)
    {
        if (!$value instanceof User) {
            $this->context->addViolation($constraint->message);
        }
    }
}
