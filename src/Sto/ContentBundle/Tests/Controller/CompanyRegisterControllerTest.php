<?php

namespace Sto\ContentBundle\Tests\Controller;

use Sto\ContentBundle\Tests\WebTestCase;
use Sto\CoreBundle\Entity\Company;
use Sto\CoreBundle\Entity\CompanyManager;
use Sto\ContentBundle\Form\Extension\ChoiceList\CompanyRegistrationStep;

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

        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Зарегистрировать профиль на best4car.ru")')->count()
        );
    }

    public function testCreateCompanyOwner()
    {
        $classes = [
            'Sto\CoreBundle\DataFixtures\ORM\LoadGroupsData',
            'Sto\CoreBundle\DataFixtures\ORM\LoadRatingGroupData',
            'Sto\CoreBundle\DataFixtures\ORM\Settings\LoadEmailTemplates',
            'Sto\CoreBundle\DataFixtures\ORM\LoadSpbData',
        ];

        $this->loadFixtures($classes);

        $this->assertCount(0, $this->getRepository('StoUserBundle:User')->findAll());

        $client = static::createClient();

        $crawler = $client->request('GET', '/registration/company');

        $token = $crawler->filter('#sto_contentbundle_registration__token')->attr('value');

        $form = [
            'sto_contentbundle_registration' => [
                'firstName' => 'Lucas',
                'username' => 'test_user',
                'email' => 'test_user@gmail.com',
                'plainPassword' => [
                    'first' => 'password',
                    'second' => 'password',
                ],
                '_token' => $token,
            ]
        ];

        $crawler = $client->request('POST', '/create-company-owner', $form);
        $this->assertCount(1, $this->getRepository('StoUserBundle:User')->findAll());

        $this->assertTrue(
            $client->getResponse()->isRedirect('/new-company/base')
        );
    }

    public function testBase()
    {
        $classes = [
            'Sto\CoreBundle\DataFixtures\ORM\LoadGroupsData',
            'Sto\CoreBundle\DataFixtures\ORM\LoadRatingGroupData',
            'Sto\CoreBundle\DataFixtures\ORM\Settings\LoadEmailTemplates',
            'Sto\CoreBundle\DataFixtures\ORM\LoadSpbData',
            'Sto\CoreBundle\DataFixtures\ORM\LoadTestUserData',
        ];

        $this->loadFixtures($classes);

        $this->assertCount(0, $this->getRepository('StoCoreBundle:Company')->findAll());

        $this->logInAsCompany();

        $crawler = $this->client->request('GET', '/new-company/base');

        $form = $crawler->selectButton('Продолжить')->form();

        $form['sto_company_register_base[fullName]'] = 'Full Company Name';
        $form['sto_company_register_base[name]'] = 'Short Company Name';
        $form['sto_company_register_base[slogan]'] = 'Slogan';
        $date = new \DateTime();
        $form['sto_company_register_base[createtDate]'] = $date->format('Y-m-d');
        $form['sto_company_register_base[city]'] = 2;

        $crawler = $this->client->submit($form);

        $this->assertTrue(
            $this->client->getResponse()->isRedirect('/company/1/edit/business-profile/')
        );

        $this->assertCount(1, $this->getRepository('StoCoreBundle:Company')->findAll());
    }

    public function createBaseCompany()
    {
        $em = $this->getEntityManager();
        $company = new Company();
        $company->setName("Short Company Name");
        $em->persist($company);

        $user = $this->getRepository('StoUserBundle:User')->findOneBy(['username' => 'manager']);
        $mark = $this->getRepository('StoCoreBundle:Mark')->findOneBy(['name' => 'Ford']);

        $manager = new CompanyManager();
        $manager->setUser($user);
        $manager->setPhone($user->getPhoneNumber());
        $manager->setCompany($company);
        $em->persist($manager);

        $company->addCompanyManager($manager);
        $company->addAuto($mark);
        $company->setRegistredFully(false);
        $company->setRegistrationStep(CompanyRegistrationStep::BUSINESS);
        $company->setVisible(false);

        $em->flush();
    }

    public function testBusinessProfile()
    {
        $classes = [
            'Sto\CoreBundle\DataFixtures\ORM\LoadGroupsData',
            'Sto\CoreBundle\DataFixtures\ORM\LoadRatingGroupData',
            'Sto\CoreBundle\DataFixtures\ORM\Settings\LoadEmailTemplates',
            'Sto\CoreBundle\DataFixtures\ORM\LoadDictionaryCompanyTypeData',
            'Sto\CoreBundle\DataFixtures\ORM\LoadDictionaryAutoServicesData',
            'Sto\CoreBundle\DataFixtures\ORM\LoadSpbData',
            'Sto\CoreBundle\DataFixtures\ORM\LoadTestUserData',
            'Sto\CoreBundle\DataFixtures\ORM\LoadAutoMarkData',
        ];

        $this->loadFixtures($classes);
        $this->createBaseCompany();
        $this->logInAsCompany();

        $crawler = $this->client->request('GET', '/company/1/edit/business-profile/');

        $token = $crawler->filter('#sto_company_register_business_profile__token')->attr('value');

        $form = [
            'sto_company_register_business_profile' => [
                'specializations' => [
                    ['type' => 1, 'subType' => 1]
                ],
                '_token' => $token,
            ],
            'services' => [
                [1]
            ]
        ];

        $crawler = $this->client->request('POST', '/company/1/edit/business-profile/', $form);

        $this->assertTrue(
            $this->client->getResponse()->isRedirect('/company/1/edit/contacts')
        );
    }

    public function testContacts()
    {
        $classes = [
            'Sto\CoreBundle\DataFixtures\ORM\LoadGroupsData',
            'Sto\CoreBundle\DataFixtures\ORM\LoadRatingGroupData',
            'Sto\CoreBundle\DataFixtures\ORM\Settings\LoadEmailTemplates',
            'Sto\CoreBundle\DataFixtures\ORM\LoadDictionaryCompanyTypeData',
            'Sto\CoreBundle\DataFixtures\ORM\LoadDictionaryAutoServicesData',
            'Sto\CoreBundle\DataFixtures\ORM\LoadSpbData',
            'Sto\CoreBundle\DataFixtures\ORM\LoadTestUserData',
            'Sto\CoreBundle\DataFixtures\ORM\LoadAutoMarkData',
        ];

        $this->loadFixtures($classes);
        $this->createBaseCompany();
        $this->logInAsCompany();

        $crawler = $this->client->request('GET', '/company/1/edit/contacts');

        $token = $crawler->filter('#sto_company_register_contacts__token')->attr('value');

        $form = [
            'sto_company_register_contacts' => [
                'address' => 'Random Address',
                'phones' => [
                    ['phone' => '+79818038624', 'description' => 'Main phone'],
                ],
                'emails' => [
                    ['email' => 'test@gmail.com', 'description' => 'Main email'],
                ],
                'workingTime' => [
                    ['days' => [3 => true], 'fromTime' => '10:00', 'tillTime' => '22:00'],
                ],
                'companyManager' => [
                    ['user' => 'manager', 'phone' => '+79183802342'],
                ],
                'gps' => '59.997031029520365,30.237803679499926',
                '_token' => $token,
            ],
        ];

        $crawler = $this->client->request('POST', '/company/1/edit/contacts', $form);

        $this->assertTrue(
            $this->client->getResponse()->isRedirect('/company/1/edit/gallery')
        );
    }
}
