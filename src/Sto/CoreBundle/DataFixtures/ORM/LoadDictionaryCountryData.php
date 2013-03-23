<?php

namespace Sto\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture,
    Doctrine\Common\DataFixtures\OrderedFixtureInterface,
    Doctrine\Common\Persistence\ObjectManager;
use Sto\CoreBundle\Entity\DictionaryCity as City,
    Sto\CoreBundle\Entity\DictionaryCountry as Country;

class LoadDictionaryCountryData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $all = [
            'Россия'    => [
                'msk' => 'Москва',
                'spb' => 'Санкт-Петербург',
                '' => 'Новосибирск',
                'ekb' => 'Екатеринбург',
                'nn' => 'Нижний Новгород',
                '' => 'Новосибирск',
                'samara' => 'Самара',
                'kzn' => 'Казань',
                '' => 'Омск',
                'chel' => 'Челябинск'
            ],
            'Беларусь'  => [
                '' => 'Минск',
                '' => 'Гомель',
                '' => 'Могилев',
                '' => 'Витебск',
                '' => 'Гродно',
                '' => 'Брест'
            ],
            'Украина'   => [
                '' => 'Киев',
                '' => 'Харьков',
                '' => 'Одесса',
                '' => 'Днепропетровск',
                '' => 'Донец',
                '' => 'Запорожье',
                '' => 'Львов'
            ],
            'Эстония'   => ['' => 'Таллин'],
            'Латвия'    => ['' => 'Рига'],
            'Литва'     => ['' => 'Вильнюс'],
            'Казахстан' => ['' => 'Алматы', '' => 'Астана'],
            'Финляндия' => [
                '' => 'Хельсинки',
                '' => 'Эспоо',
                '' => 'Тампере',
                '' => 'Вантаа',
                '' => 'Турку',
                '' => 'Рованиеми',
                '' => 'Куопио',
                '' => 'Лахти',
                '' => 'Иматра',
                '' => 'Лаппеенранта',
                '' => 'Котка'
            ],
            'Швеция'    => ['' => 'Стокгольм', '' => 'Гётеборг',],
            'Норвегия'  => ['' => 'Осло'],
        ];
        $codes = [
            'Россия' => 'RU',
            'Беларусь' => 'BY',
            'Украина' => 'UA',
            'Эстония' => 'EE',
            'Латвия' => 'LV',
            'Литва' => 'LT',
            'Казахстан' => 'KZ',
            'Финляндия' => 'FI',
            'Швеция' => 'SE',
            'Норвегия' => 'NO'
        ];

        foreach ($all as $country => $cities) {
            $vCountry = new Country;
            $vCountry->setName($country);
            $vCountry->setShortName($codes[$country]);

            chdir(__DIR__ . '/../../../../../');
            $from = "app/Resources/fixtures/countries/".strtolower($codes[$country]).".png";
            $to = "web/storage/images/countries/".strtolower($codes[$country]).".png";

            if (!file_exists($from))
                $from = "app/Resources/fixtures/countries/eu.png";

            if (!is_dir(dirname($to))) {
                mkdir(dirname($to), 0755, true);
            }

            if (!file_exists($to)) {
                copy($from, $to);
            }
            $vCountry->setIconName(strtolower($codes[$country]) . '.png');

            $manager->persist($vCountry);

            foreach ($cities as $shortName => $city) {
                $vCity = new City;
                $vCity->setName($city);
                $vCity->setParent($vCountry);
                $vCity->setShortName($shortName ? $shortName : 'country');
                $manager->persist($vCity);
            }
            $manager->flush();
        }
    }

    public function getOrder()
    {
        return 25;
    }
}
