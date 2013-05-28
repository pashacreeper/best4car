<?php

namespace Sto\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Sto\CoreBundle\Entity\Dictionary;

class LoadDictionaryWorkData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $works = [
            [
                'name' =>'Диагностика',
                'description' => 'проверка, осмотр, выявление неисправности(-ей) поиск неисправности(-ей)',
            ],
            [
                'name' =>'Замена',
                'description' => 'установка новой з/ч, нового узла',
            ],
            [
                'name' =>'Обслуживание',
                'description' => 'чистка и смазка, удаление загрязнений',
            ],
            [
                'name' =>'Демонтаж-монтаж',
                'description' => 'снятие и установка, переустановка',
            ],
            [
                'name' =>'Установка',
                'description' => 'инсталляция, внедрение',
            ],
            [
                'name' =>'Ремонт',
                'description' => 'восстановление, переборка',
            ],
            [
                'name' =>'Регулировка',
                'description' => 'калибровка, приведение к допустимым параметрам',
            ],
            [
                'name' =>'Окраска',
                'description' => 'покраска, грунтовка, нанесение лкп, нанесение лакокрасочного покрытия',
            ],
            [
                'name' =>'Покрытие',
                'description' => 'нанесение на поверхность',
            ],
            [
                'name' =>'Промывка',
                'description' => 'очистка, удаление загрязнений и отложений',
            ],
            [
                'name' =>'Изготовление',
                'description' => 'выпуск, производство',
            ],
            [
                'name' =>'Балансировка',
                'description' => 'устранение дисбаланса, достижение баланса',
            ],
            [
                'name' =>'Перетяжка',
                'description' => 'замена покрытия, изготовление и установка обшивок',
            ],
            [
                'name' =>'Проведение регламентных работ по объёму ТО',
                'description' => 'выполнение работ по проведению ТО',
            ],
            [
                'name' =>'Зарядка',
                'description' => 'пополнение заряда.',
            ],
            [
                'name' =>'Програмирование',
                'description' => 'перепрограмирование, прошивка, перепрошивка, регистрация',
            ],
            [
                'name' =>'Смазка',
                'description' => 'нанесение смазывающего материала',
            ],
            [
                'name' =>'Заправка',
                'description' => 'наполнение',
            ],
            [
                'name' =>'Герметизация',
                'description' => 'устранение течи, протечек',
            ],
            [
                'name' =>'Форсирование',
                'description' => 'чип-тюнинг, повышение мощности и эффективности'
            ],
        ];

        foreach ($works as $work) {
            $dictionary = (new Dictionary\Work)
                ->setName($work['name'])
                ->setDescription($work['description'])
            ;
            $manager->persist($dictionary);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 16;
    }
}
