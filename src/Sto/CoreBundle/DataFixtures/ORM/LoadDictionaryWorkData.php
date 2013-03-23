<?php

namespace Sto\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture,
    Doctrine\Common\DataFixtures\OrderedFixtureInterface,
    Doctrine\Common\Persistence\ObjectManager;
use Sto\CoreBundle\Entity\DictionaryWork;

class LoadDictionaryWorkData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $works = [
            'Диагностика = проверка, осмотр, выявление неисправности(-ей) поиск неисправности(-ей)',
            'Замена = установка новой з/ч, нового узла',
            'Обслуживание = чистка и смазка, удаление загрязнений',
            'Демонтаж-монтаж = снятие и установка, переустановка',
            'Установка = инсталляция, внедрение',
            'Ремонт = восстановление, переборка',
            'Регулировка = калибровка, приведение к допустимым параметрам',
            'Окраска = покраска, грунтовка, нанесение лкп, нанесение лакокрасочного покрытия',
            'Покрытие = нанесение на поверхность',
            'Промывка = очистка, удаление загрязнений и отложений',
            'Изготовление = выпуск, производство',
            'Балансировка = устранение дисбаланса, достижение баланса',
            'Перетяжка = замена покрытия, изготовление и установка обшивок',
            'Проведение регламентных работ по объёму ТО , выполнение работ по проведению ТО',
            'Зарядка = пополнение заряда.',
            'Програмирование = перепрограмирование, прошивка, перепрошивка, регистрация',
            'Смазка = нанесение смазывающего материала',
            'Заправка = наполнение',
            'Герметизация = устранение течи, протечек',
            'Форсирование = чип-тюнинг, повышение мощности и эффективности'
        ];

        foreach ($works as $name) {
            $dictionary = (new DictionaryWork)
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
