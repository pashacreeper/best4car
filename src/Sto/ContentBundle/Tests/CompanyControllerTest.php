<?php
/**
 * Created by PhpStorm.
 * User: Sergey
 * Date: 03.02.14
 * Time: 18:59
 */

namespace Sto\ContentBundle\Tests;

use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class CompanyControllerTest extends WebTestCase
{
    public function test_main_page_opens_correct()
    {
        $client = static::createClient();

        $this->loadFixtures([
            'Sto\CoreBundle\DataFixtures\ORM\LoadDictionaryCountryData'
        ]);

        $client->request('GET', '/');
        $this->assertEquals(
            Response::HTTP_OK,
            $client->getResponse()->getStatusCode()
        );
    }
}
