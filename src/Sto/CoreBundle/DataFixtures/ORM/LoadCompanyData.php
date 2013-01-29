<?php

namespace Sto\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

use sto\CoreBundle\Entity\Company;

class LoadCompanyData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $company = new Company();
        $company->setName('Автосервис Altauto');
        $company->setSlogan('not');
        $company->setFullName('Автосервис Altauto');
        $company->setWeb('www.altauto.ru');
        $company->setSpecialization('CТО, Паркинг, Мойка');
        $company->setServices('Кузовной ремонт,Покраска, Слесарка, Электрика, ДВС, КПП, АКПП, Шиномонтаж, Развал-схождение, Запчасти, Комп диагностика');
        $company->setAdditionalServices('Кофе, WiFi');
        $company->setLogo('LOGO');
        $company->setWorkingTime('10:00 - 20:00');
        $company->setPhones('+7 495 789-83-37');
        $company->setSkype('altauto');
        $company->setEmail('info@altauto.ru');
        $company->setAddress('ул. Леснорядская 2-я, 13 Москва, Россия 107140‎');
        $company->setGps('1111');
        $company->setCreatetDate(new \DateTime('2010-08-12'));
        $company->setPhotos('Photos');
        $company->setSocialNetworks('Facebook, Vk, Google+');
        $company->setRating(9.2);
        $company->setReviews('1,2,3,4,5');
        $company->setDeals('1,2,3,4,5');
        $company->setDescription('Компания Альтавто, один из лидеров на рынке обслуживания автомобилей производства США, сошедших с гарантии, имеет за плечами 6-ти летний опыт успешной работы, постоянно развивающийся сервис.
Альтавто - это команда професионалов, обладающая огромным опытом работы с автомобилями крайслер, джип, додж, форд, шевролет, кадилак.');
        $company->setSubscribable('Автомеханики обеспечены широкопрофильными комплектами инструмента, наличие и асортимент которых постоянно проверяется и расширяется.');
        $company->setHourPrice('100 р.');
        $company->setManagers('1, 2, 3, 4');
        $company->setAdministratorContactInfo('Not info');
        $company->setVisible(true);
        $company->setNotes('sdfsd');

        $manager->persist($company);
        $manager->flush();
    }

    public function getOrder()
    {
        return 3;
    }
}
