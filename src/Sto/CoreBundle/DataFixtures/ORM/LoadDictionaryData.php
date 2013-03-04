<?php

namespace Sto\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture,
    Doctrine\Common\DataFixtures\OrderedFixtureInterface,
    Doctrine\Common\Persistence\ObjectManager;
use Sto\CoreBundle\Entity\Dictionary;

class LoadDictionaryData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $dictionary = new Dictionary;
        $dictionary->setName('Типы организаций'); // id = 1
        $manager->persist($dictionary);

        $add_service = new Dictionary;
        $add_service->setName('Дополнительные услуги'); // id = 2
        $manager->persist($add_service);

        $car_job = new Dictionary;
        $car_job->setName('Перечень действий / работ с узлами автомобиля'); // id = 3
        $manager->persist($car_job);

        $currency = new Dictionary;
        $currency->setName('Справочник валют'); // id = 4
        $manager->persist($currency);

        $currency_level = new Dictionary;
        $currency_level->setName('Уровень цен'); // id = 5
        $manager->persist($currency_level);

        $kind_of_activity = new Dictionary;
        $kind_of_activity->setName('Род деятельности'); // id = 6
        $manager->persist($kind_of_activity);

        $manager->flush();

        $org_types = [];
        $org_types[] = 'СТО';
        $org_types[] = 'A-1. Официальная дилерская СТО';
        $org_types[] = 'A-2. Независимая (недилерская) монобрэндовая СТО';
        $org_types[] = 'A-3. Независимая недилерская) мультибрэндовая СТО';
        $org_types[] = 'Автосалон';
        $org_types[] = 'B-1. Официальный дилер, мононбрэндовый автосалон';
        $org_types[] = 'B-2. Независимый монобрэндовый автосалон';
        $org_types[] = 'B-3. Независимый мультибрэндовый автосалон';
        $org_types[] = 'Паркинг';
        $org_types[] = 'С-1. Охраняемый отапливаемый паркинг';
        $org_types[] = 'С-2. Охраняемый неотапливаемый паркинг';
        $org_types[] = 'С-3. Охраняемая открытая автостоянка';
        $org_types[] = 'Ремонт агрегатов и блоков управления';
        $org_types[] = 'D-1. Специализированная мастерская по ремонту узлов и агрегатов, без возможности снятия и установки их с автомобиля в условиях мастерской.';
        $org_types[] = 'D-2. Специализированная мастерская по ремонту узлов и агрегатов, с возможностью снятия и установки их с автомобиля в условиях мастерской';
        $org_types[] = 'Мойка';
        $org_types[] = 'E-1. Ручная мойка';
        $org_types[] = 'E-2. Автоматическая портальная мойка';
        $org_types[] = 'E-3. Автоматическая проездная мойка самообслуживания, без участия персонала';
        $org_types[] = 'E-4. Автомоечный комплекс с различными видами моек и дополнительных услуг';
        $org_types[] = 'Шиномонтаж';
        $org_types[] = 'F-1. Шиномонтажная мастерская стационарная';
        $org_types[] = 'F-2. Мобильный шиномонтаж';
        $org_types[] = 'Юридические услуги';
        $org_types[] = 'G-1. Юридические компании';
        $org_types[] = 'G-2. Индивидуальный юридический консультант (частная практика)';
        $org_types[] = 'G-3. Общество защиты прав потребителей';
        $org_types[] = 'Экспертиза';
        $org_types[] = 'Н-1. Экспертная компания';
        $org_types[] = 'Н-2 Экспертное сообщество';
        $org_types[] = 'Эвакуация и помощь на дороге';
        $org_types[] = 'I-1. Эвакуационная компания со своим автопарком эвакуаторов';
        $org_types[] = 'I-2. Компания оказывающая услуги по помощи автовладельцам на дороге, но не имеющая автоэвакуатора';
        $org_types[] = 'Страхование';
        $org_types[] = 'J-1. Страховая компания';
        $org_types[] = 'J-2. Страховой агент';
        $org_types[] = 'J-3. Страховой брокер';
        $org_types[] = 'ГосТехОсмотр';
        $org_types[] = 'К-1. Государственный пункт прохождения ГТО';
        $org_types[] = 'К-2. Коммерческий пункт прохождения ГТО';
        $org_types[] = 'К-3. СТО уполномоченная проводить ГТО';
        $org_types[] = 'Установка сигнализаций';
        $org_types[] = 'L-1. Центр охранных систем';
        $org_types[] = 'L-2. Тюнинговая мастерская с широким набором услуг';
        $org_types[] = 'МРЭО';
        $org_types[] = 'М-1. Государственное МРЭО';
        $org_types[] = 'М-2. Коммерческое МРЭО';
        $org_types[] = 'Дополнительное оборудование';
        $org_types[] = 'N-1. Тюнинговая мастерская';
        $org_types[] = 'N-2. Установочный центр дополнительного оборудования';
        $org_types[] = 'Автошкола';
        $org_types[] = 'О-1. Мотошкола';
        $org_types[] = 'О-2. Автошкола государственная';
        $org_types[] = 'О-3. Автошкола коммерческая';
        $org_types[] = 'О-3. Курсы контр-аварийной подготовки';
        $org_types[] = 'О-4. Курсы повышения водительского мастерства';
        $org_types[] = 'О-5. Секции и школы авто-мото спортивного направления';
        $org_types[] = 'Запасные части';
        $org_types[] = 'Р-1. Поставщик новых запчастей';
        $org_types[] = 'Р-2. Поставщик аксесуаров';
        $org_types[] = 'Р-3. Авторазборка';
        $org_types[] = 'Р-4. Поставщик запчастей бывших в употреблении и восстановленных агрегатов';
        $org_types[] = 'Прокат автомобилей';
        $org_types[] = 'Q-1. Компания предоставляющая автомобили и автобусы с водителем (лимузины, патибасы) на торжественные мероприятия';
        $org_types[] = 'Q-2. Компания предоставляющая автомобили в прокат для повседневного пользования с водителем и без него';
        $org_types[] = 'Q-3. Компания предоставляющая мототехнику и автотехнику для участия в спортивных и увеселительных мероприятиях';
        $org_types[] = 'R. Водительская медкомиссия';
        $org_types[] = 'X. Автозаправочные станции';
        $org_types[] = 'Y. Таксомоторные компании';

        $add_services = [];
        $add_services[] = 'Подменный автомобиль';
        $add_services[] = 'Терминал оплаты по безналичному расчету, по банковской карте';
        $add_services[] = 'Терминал быстрой оплаты мобильной связи, интернета и других услуг';
        $add_services[] = 'Автобус до станции метро';
        $add_services[] = 'Такси со скидкой (без оплаты)';
        $add_services[] = 'Кондиционер';
        $add_services[] = 'Банкомат';
        $add_services[] = 'Wi-Fi';
        $add_services[] = 'Клиентская зона, зона ожидания';
        $add_services[] = 'Клиентская зона, зона ожидания с TV';
        $add_services[] = 'Детская комната';
        $add_services[] = 'Кафе';
        $add_services[] = 'Кофе-машина';
        $add_services[] = 'Аппарат по продаже напитков и шоколадных батончиков';
        $add_services[] = 'Эвакуатор';

        $car_jobs = [];
        $car_jobs[] = 'Диагностика=проверка, осмотр, выявление неисправности(-ей) поиск неисправности(-ей)';
        $car_jobs[] = 'Замена=установка новой з/ч, нового узла';
        $car_jobs[] = 'Обслуживание=чистка и смазка, удаление загрязнений';
        $car_jobs[] = 'Демонтаж-монтаж = снятие и установка, переустановка';
        $car_jobs[] = 'Установка=инсталляция, внедрение';
        $car_jobs[] = 'Ремонт=восстановление, переборка';
        $car_jobs[] = 'Регулировка=калибровка, приведение к допустимым параметрам';
        $car_jobs[] = 'Окраска=покраска, грунтовка, нанесение лкп, нанесение лакокрасочного покрытия';
        $car_jobs[] = 'Покрытие=нанесение на поверхность';
        $car_jobs[] = 'Промывка=очистка, удаление загрязнений и отложений';
        $car_jobs[] = 'Изготовление=выпуск, производство';
        $car_jobs[] = 'Балансировка=устранение дисбаланса, достижение баланса';
        $car_jobs[] = 'Перетяжка=замена покрытия, изготовление и установка обшивок';
        $car_jobs[] = 'Проведение регламентных работ по объёму ТО , выполнение работ по проведению ТО';
        $car_jobs[] = 'Зарядка=пополнение заряда.';
        $car_jobs[] = 'Програмирование=перепрограмирование, прошивка, перепрошивка, регистрация';
        $car_jobs[] = 'Смазка=нанесение смазывающего материала';
        $car_jobs[] = 'Заправка= наполнение';
        $car_jobs[] = 'Герметизация= устранение течи, протечек';
        $car_jobs[] = 'Форсирование=чип-тюнинг, повышение мощности и эффективности';

        $currencys = [];
        $currencys[] = 'Российский рубль';
        $currencys[] = 'Доллар';
        $currencys[] = 'Евро';
        $currencys[] = 'Гривна';

        $currency_levels = [];
        $currency_levels[] = 'Низкий';
        $currency_levels[] = 'Средний';
        $currency_levels[] = 'Приемлимый';
        $currency_levels[] = 'Высокий';

        $kinds_of_activity = [];
        $kinds_of_activity[] = 'не указан';
        $kinds_of_activity[] = 'учащийся';
        $kinds_of_activity[] = 'студент';
        $kinds_of_activity[] = 'гос служащий';
        $kinds_of_activity[] = 'бизнесмен';
        $kinds_of_activity[] = 'рабочий';
        $kinds_of_activity[] = 'офисный работник';
        $kinds_of_activity[] = 'руководитель среднего звена';
        $kinds_of_activity[] = 'владелец бизнеса';
        $kinds_of_activity[] = 'иждивенец';
        $kinds_of_activity[] = 'военный';
        $kinds_of_activity[] = 'научный работник';
        $kinds_of_activity[] = 'профессиональный спортсмен';
        $kinds_of_activity[] = 'домохозяйка';
        $kinds_of_activity[] = 'фермер';
        $kinds_of_activity[] = 'пенсионер';

        foreach ($currencys as $cur_name) {
            $d = new Dictionary;
            $d->setName($cur_name);
            $d->setParent($currency);
            $manager->persist($d);
        }

        foreach ($org_types as $type_name) {
            $d = new Dictionary;
            $d->setName($type_name);
            // $d->setParentId($org_type_id);
            $d->setParent($dictionary);
            $manager->persist($d);
        }

        foreach ($add_services as $name) {
            $d = new Dictionary;
            $d->setName($name);
            $d->setParent($add_service);
            $manager->persist($d);
        }

        foreach ($car_jobs as $name) {
            $d = new Dictionary;
            $d->setName($name);
            $d->setParent($car_job);
            $manager->persist($d);
        }

        foreach ($currency_levels as $name) {
            $d = new Dictionary;
            $d->setName($name);
            $d->setParent($currency_level);
            $manager->persist($d);
        }

        foreach ($kinds_of_activity as $name) {
            $d = new Dictionary;
            $d->setName($name);
            $d->setParent($kind_of_activity);
            $manager->persist($d);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }
}
