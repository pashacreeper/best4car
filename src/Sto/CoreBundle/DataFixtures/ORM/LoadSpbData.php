<?php

namespace Sto\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Sto\CoreBundle\Entity\Country;

class LoadSpbData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $vCountry = new Country;
        $vCountry->setName('Россия');
        $vCountry->setShortName('RU');

        chdir(__DIR__ . '/../../../../../');
        $from = "app/Resources/fixtures/countries/ru.png";
        $to = "web/storage/images/countries/ru.png";

        if (!file_exists($from))
            $from = "app/Resources/fixtures/countries/eu.png";

        if (!is_dir(dirname($to))) {
            mkdir(dirname($to), 0755, true);
        }

        if (!file_exists($to)) {
            copy($from, $to);
        }
        $vCountry->setIconName('ru.png');

        $manager->persist($vCountry);

        $vCity = new Country;
        $vCity->setName('Санкт-Петербург');
        $vCity->setGps('59.91789232285739,30.325057499999957');
        $vCity->setParent($vCountry);
        $vCity->setShortName('spb');
        $manager->persist($vCity);

        $manager->flush();
    }

    public function getOrder()
    {
        return 15;
    }
}
