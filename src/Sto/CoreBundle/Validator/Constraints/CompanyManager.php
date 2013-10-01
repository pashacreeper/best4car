<?php
namespace Sto\CoreBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class CompanyManager extends Constraint
{
    public $message = 'Не верный логин менеджера, пользователь не найден на сайте';

    public function validatedBy()
    {
        return 'company_manager_validator';
    }
}
