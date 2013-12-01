<?php
namespace Sto\UserBundle\Services;

use Symfony\Component\Security\Core\SecurityContext;
use Sto\UserBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class AuthenticateUserService
{
    /**
     * @var SecurityContext
     */
    protected $securityContext;

    public function __construct(SecurityContext $securityContext)
    {
        $this->securityContext = $securityContext;
    }

    /**
     * Authentificate user
     * @param User $user
     */
    public function authenticate(User $user)
    {
        $providerKey = 'main'; // your firewall name
        $token = new UsernamePasswordToken($user, null, $providerKey, $user->getRoles());

        $this->securityContext->setToken($token);
    }
}
