<?php

namespace Sto\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture,
    Doctrine\Common\DataFixtures\OrderedFixtureInterface,
    Doctrine\Common\Persistence\ObjectManager;
use Sto\CoreBundle\Entity\DictionaryAdditionalService;

class LoadDictionaryAdditionalServiceData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $additionalService = ['Подменный автомобиль', 'Терминал оплаты по безналичному расчету, по банковской карте', 'Терминал быстрой оплаты мобильной связи, интернета и других услуг', 'Автобус до станции метро', 'Такси со скидкой (без оплаты)', 'Кондиционер', 'Банкомат', 'Wi-Fi', 'Клиентская зона, зона ожидания', 'Клиентская зона, зона ожидания с TV', 'Детская комната', 'Кафе', 'Кофе-машина', 'Аппарат по продаже напитков и шоколадных батончиков', 'Эвакуатор'];

        foreach ($additionalService as $key => $name) {
            $dictionary = (new DictionaryAdditionalService)
                ->setName($name)
            ;

            $manager->persist($dictionary);
            $this->addReference("additionalService[{$key}]", $dictionary);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 22;
    }
}
