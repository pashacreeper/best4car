<?php

namespace Sto\ContentBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class CompanyRegisterControllerTest extends WebTestCase
{
    public function testUserRegistration()
    {
    	$classes = [
            'Sto\CoreBundle\DataFixtures\ORM\LoadSpbData',
        ];

        $this->loadFixtures($classes);

        $client = static::createClient();

        $crawler = $client->request('GET', '/registration/company');

        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Зарегистрировать профиль на best4car.ru")')->count()
        );
    }
}