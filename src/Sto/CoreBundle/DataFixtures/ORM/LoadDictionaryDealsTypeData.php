<?php

namespace Sto\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Sto\CoreBundle\Entity\Dictionary;
use Sto\CoreBundle\Entity\DealType;

class LoadDictionaryDealsTypeData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $works = [
            'Скидка',
            'Маркетинговое мероприятие',
            'Тест-драйв',
            'Презентация, день открытых дверей.',
            'Распродажа',
            'Сезонное предложение'
        ];

        foreach ($works as $key => $name) {
            $dictionary = (new DealType)
                ->setName($name)
                ->setPosition($key)
            ;
            $manager->persist($dictionary);
            $this->addReference("dealsTypes[{$key}]", $dictionary);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 13;
    }
}
