<?php

namespace Sto\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

use sto\CoreBundle\Entity\Country;
use sto\CoreBundle\Entity\City;

class LoadCountryData extends AbstractFixture implements OrderedFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $all = array(
            'Россия'    => array('Москва','Санкт-Петербург','Новосибирск','Екатеринбург','Нижний Новгород','Новосибирск','Самара','Казань','Омск','Челябинск'),
            'Беларусь'  => array( 'Минск','Гомель','Могилев','Витебск','Гродно','Брест'),
            'Украина'   => array('Киев','Харьков','Одесса','Днепропетровск','Донец','Запорожье','Львов'),
            'Эстония'   => array('Таллин'),
            'Латвия'    => array('Рига'),
            'Литва'     => array('Вильнюс'),
            'Казахстан' => array('Алматы','Астана'),
            'Финляндия' => array('Хельсинки','Эспоо','Тампере','Вантаа','Турку','Рованиеми','Куопио','Лахти','Иматра','Лаппеенранта','Котка',),
            'Швеция'    => array('Стокгольм','Гётеборг',),
            'Норвегия'  => array('Осло'),
        );

        $codes = array('Россия' => 'ru', 'Беларусь' => 'by', 'Украина' => 'ua', 'Эстония' => 'es', 'Латвия' => 'lv', 'Литва' => 'lt', 'Казахстан' => 'kz', 'Финляндия' => 'fn', 'Швеция' => 'se', 'Норвегия' => 'no');

        foreach ($all as $country => $cities) {
            $vCountry = new Country();
            $vCountry->setName($country);
            $vCountry->setCode($codes[$country]);
            // iconq

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
            // $vCountry->setIcon('images/countries/' . strtolower($codes[$country]) . '.png');
            $vCountry->setIconName(strtolower($codes[$country]) . '.png');

            // images

            $manager->persist($vCountry);

            foreach ($cities as $city) {
                $vCity = new City();
                $vCity->setName($city);
                $vCity->setCountry($vCountry);
                $vCity->setCode('country');
                $manager->persist($vCity);
            }
            $manager->flush();
        }

    }

    public function getOrder()
    {
        return 2;
    }
}
