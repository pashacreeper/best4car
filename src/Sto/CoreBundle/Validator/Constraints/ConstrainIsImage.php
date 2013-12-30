<?php
namespace Sto\CoreBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class ConstrainIsImage extends Constraint
{
    public $message = "Для добавления фотографии в галерею необходимо выбрать файл для отправки.";
}
