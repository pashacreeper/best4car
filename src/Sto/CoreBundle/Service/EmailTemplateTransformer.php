<?php
namespace Sto\CoreBundle\Service;

use Sto\CoreBundle\Entity\Company;
use Sto\UserBundle\Entity\User;

class EmailTemplateTransformer
{
    /**
     * Transforming tempalte string to message
     * @param  string  $template Template string
     * @param  User    $user
     * @param  Company $company
     * @return string  transformed template string
     */
    public function transform($template, User $user = null, Company $company = null)
    {
        return true;
    }
}
