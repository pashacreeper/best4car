<?php

namespace Sto\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Sto\CoreBundle\Entity\Dictionary;

class LoadDictionaryAdditionalServiceData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $additionalService = [['evaquate','Подменный автомобиль'], ['credit-card','Терминал оплаты по безналичному расчету, по банковской карте'], ['credit-card','Терминал быстрой оплаты мобильной связи, интернета и других услуг'], ['metro','Автобус до станции метро'], ['evaquate','Такси со скидкой (без оплаты)'], ['waiting-room','Кондиционер'], ['credit-card','Банкомат'], ['wifi','Wi-Fi'], ['waiting-room','Клиентская зона, зона ожидания'], ['waiting-room','Клиентская зона, зона ожидания с TV'], ['waiting-room','Детская комната'], ['coffee','Кафе'], ['coffee','Кофе-машина'], ['coffee','Аппарат по продаже напитков и шоколадных батончиков'], ['evaquate','Эвакуатор']];

        foreach ($additionalService as $key => $service) {
            $dictionary = (new Dictionary\AdditionalService)
                ->setShortName($service[0])
                ->setName($service[1])
            ;

            $manager->persist($dictionary);
            $this->addReference("additionalService[{$key}]", $dictionary);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 12;
    }
}
