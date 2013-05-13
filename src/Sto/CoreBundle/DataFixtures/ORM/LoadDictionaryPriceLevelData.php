<?php

namespace Sto\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Sto\CoreBundle\Entity\Dictionary;

class LoadDictionaryPriceLevelData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $currencys = [
            'Дешево',
            'Нормально',
            'Дорого'
        ];

        foreach ($currencys as $i => $name) {
            $dictionary = (new Dictionary\PriceLevel)
                ->setName($name)
            ;

            $manager->persist($dictionary);
            $this->addReference("price_level[{$i}]", $dictionary);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 18;
    }
}
