<?php
namespace Sto\CoreBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\ORM\EntityManager;

class CompanyManagerValidator extends ConstraintValidator
{
    private $em;

    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    public function validate($value, Constraint $constraint)
    {
        $user = $this->em->getRepository('StoUserBundle:User')->findOneBy(['username' => (string) $value]);
        if (!$user) {
            $this->context->addViolation($constraint->message);
        }
    }
}
