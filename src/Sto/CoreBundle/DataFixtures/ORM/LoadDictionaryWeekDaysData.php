<?php

namespace Sto\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Sto\CoreBundle\Entity\Dictionary;

class LoadDictionaryWeekDaysData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $currencys = [
            'Пн' => 'Понедельник',
            'Вт' => 'Вторник',
            'Ср' => 'Среда',
            'Чт' => 'Четверг',
            'Пт' => 'Пятница',
            'Сб' => 'Суббота',
            'Вс' => 'Воскресенье'
        ];

        $i = 0;
        foreach ($currencys as $shortName => $name) {
            $dictionary = (new Dictionary\WeekDay)
                ->setShortName($shortName)
                ->setName($name)
                ->setPosition($i)
            ;

            $manager->persist($dictionary);
            $this->addReference("week_day[{$i}]", $dictionary);
            $i++;
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 17;
    }
}
