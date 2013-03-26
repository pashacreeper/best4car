<?php

namespace Sto\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture,
    Doctrine\Common\DataFixtures\OrderedFixtureInterface,
    Doctrine\Common\Persistence\ObjectManager;
use Sto\CoreBundle\Entity\DictionaryDealsType;

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

        foreach ($works as $name) {
            $dictionary = (new DictionaryDealsType)
                ->setName($name)
            ;
            $manager->persist($dictionary);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 23;
    }
}
