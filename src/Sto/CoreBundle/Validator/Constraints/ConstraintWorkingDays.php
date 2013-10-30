<?php


namespace Sto\CoreBundle\Validator\Constraints;


use Symfony\Component\Validator\Constraint;

class ConstraintWorkingDays extends Constraint
{
    public $message = "Необходимо выбрать хотя бы один рабочий день";
} 