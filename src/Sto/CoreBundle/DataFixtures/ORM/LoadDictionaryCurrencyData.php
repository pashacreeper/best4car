<?php

namespace Sto\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
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

        $i = 0;
        foreach ($currencys as $shortName => $name) {
            $dictionary = (new Dictionary\Currency)
                ->setShortName($shortName)
                ->setName($name)
            ;

            $manager->persist($dictionary);
            $this->addReference("currencies[{$i}]", $dictionary);
            $i++;
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 14;
    }
}
