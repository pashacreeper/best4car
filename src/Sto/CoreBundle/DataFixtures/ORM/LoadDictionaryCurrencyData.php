<?php

namespace Sto\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture,
    Doctrine\Common\DataFixtures\OrderedFixtureInterface,
    Doctrine\Common\Persistence\ObjectManager;
use Sto\CoreBundle\Entity\Dictionary;

class LoadDictionaryCurrencyData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $currencys = [
            'RUB' => 'Российский рубль',
            'USD' => 'Доллар',
            'EUR' => 'Евро',
            'UAH' => 'Гривна',
            'BYR' => 'Белорусский рубль',
            'KZT' => 'Казахстанский тенге',
            'LVL' => 'Латвийский лат',
            'LTL' => 'Литовский лит',
            'SEK' => 'Шведская крона',
            'NOK' => 'Норвежская крона'
        ];

        foreach ($currencys as $shortName => $name) {
            $dictionary = (new Dictionary\Currency)
                ->setShortName($shortName)
                ->setName($name)
            ;

            $manager->persist($dictionary);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 24;
    }
}
