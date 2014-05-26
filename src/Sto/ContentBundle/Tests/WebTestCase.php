<?php

namespace Sto\ContentBundle\Tests;

use Liip\FunctionalTestBundle\Test\WebTestCase as BaseWebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

abstract class WebTestCase extends BaseWebTestCase
{
    protected $client = null;

    public function setUp()
    {
        $this->client = static::createClient();
    }

    public function getEntityManager()
    {
        return $this->getContainer()->get('doctrine')->getManager();
    }

    public function getRepository($name)
    {
        return $this->getEntityManager()->getRepository($name);
    }

    protected function logInAsCompany()
    {
        $session = $this->client->getContainer()->get('session');

        $user = $this->getRepository('StoUserBundle:User')->findOneBy(['username' => 'manager']);

        $firewall = 'main';
        $token = new UsernamePasswordToken($user, null, $firewall, array('ROLE_MANAGER', 'ROLE_USER'));
        $session->set('_security_'.$firewall, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }
}
