<?php

namespace Sto\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture,
    Doctrine\Common\DataFixtures\OrderedFixtureInterface,
    Doctrine\Common\Persistence\ObjectManager;
use Sto\CoreBundle\Entity\Dictionary;

class LoadDictionaryCountryData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $all = [
            'Россия'    => [
                'msk' => 'Москва',
                'spb' => 'Санкт-Петербург',
                'nov' => 'Новосибирск',
                'ekb' => 'Екатеринбург',
                'nn'  => 'Нижний Новгород',
                'sam' => 'Самара',
                'kzn' => 'Казань',
                'oms' => 'Омск',
                'chel' => 'Челябинск'
            ],
            'Беларусь'  => [
                'min' => 'Минск',
                'gom' => 'Гомель',
                'mog' => 'Могилев',
                'vit' => 'Витебск',
                'gro' => 'Гродно',
                'bre' => 'Брест'
            ],
            'Украина'   => [
                'kie' => 'Киев',
                'har' => 'Харьков',
                'ode' => 'Одесса',
                'dne' => 'Днепропетровск',
                'don' => 'Донец',
                'zap' => 'Запорожье',
                'lvo' => 'Львов'
            ],
            'Эстония'   => ['tal' => 'Таллин'],
            'Латвия'    => ['rig' => 'Рига'],
            'Литва'     => ['vil' => 'Вильнюс'],
            'Казахстан' => ['alm' => 'Алматы', 'ast' => 'Астана'],
            'Финляндия' => [
                'hel' => 'Хельсинки',
                'esp' => 'Эспоо',
                'tam' => 'Тампере',
                'van' => 'Вантаа',
                'tur' => 'Турку',
                'row' => 'Рованиеми',
                'kuo' => 'Куопио',
                'lah' => 'Лахти',
                'ima' => 'Иматра',
                'lap' => 'Лаппеенранта',
                'cot' => 'Котка'
            ],
            'Швеция'    => ['sto' => 'Стокгольм', 'get' => 'Гётеборг',],
            'Норвегия'  => ['osl' => 'Осло'],
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
            $vCountry = new Dictionary\Country;
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
                $vCity = new Dictionary\Country;
                $vCity->setName($city);
                $vCity->setParent($vCountry);
                $vCity->setShortName($shortName ? $shortName : 'CODE');
                $manager->persist($vCity);
            }
            $manager->flush();
        }
    }

    public function getOrder()
    {
        return 15;
    }
}
