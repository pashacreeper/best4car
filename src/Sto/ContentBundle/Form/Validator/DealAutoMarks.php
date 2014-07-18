<?php

namespace Sto\ContentBundle\Form\Validator;

use Symfony\Component\Validator\Constraint;

class DealAutoMarks extends Constraint
{
    public $message = 'Необходимо выбрать хотя бы одну марку.';
}