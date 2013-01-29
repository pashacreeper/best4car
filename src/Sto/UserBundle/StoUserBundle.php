<?php

namespace Sto\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class StoUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
