<?php

namespace Sto\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Sto\CoreBundle\Entity\Dictionary;

class LoadDictionaryCountryData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $all = [
            'Россия'    => [
                'msk' => ['name' => 'Москва', 'gps' => '55.75065752621757,37.63249495000002'],
                'spb' => ['name' => 'Санкт-Петербург', 'gps' => '59.91789232285739,30.325057499999957'],
                'nov' => ['name' => 'Новосибирск', 'gps' => '54.97033606136859,82.94940489999999'],
                'ekb' => ['name' => 'Екатеринбург', 'gps' => '56.81411662586341,60.654933450000044'],
                'nn'  => ['name' => 'Нижний Новгород', 'gps' => '56.2928916147848,43.92674520000003'],
                'sam' => ['name' => 'Самара', 'gps' => '53.261247509296126,50.198076950000086'],
                'kzn' => ['name' => 'Казань', 'gps' => '55.79570143087383,49.07330300000001'],
                'oms' => ['name' => 'Омск', 'gps' => '54.98617900587369,73.35613894999994'],
                'chel' => ['name' => 'Челябинск', 'gps' => '55.159582474186,61.41054559999998'],
            ],
            'Беларусь'  => [
                'min' => ['name' => 'Минск', 'gps' => '53.883980386469474,27.59497405000002'],
                'gom' => ['name' => 'Гомель', 'gps' => '52.42541477447133,30.960416699999996'],
                'mog' => ['name' => 'Могилев', 'gps' => '53.901556916940095,30.351576799999975'],
                'vit' => ['name' => 'Витебск', 'gps' => '55.191155258040375,30.19536489999996'],
                'gro' => ['name' => 'Гродно', 'gps' => '53.667222385512716,23.8528632'],
                'bre' => ['name' => 'Брест', 'gps' => '52.09197936996528,23.725662199999988'],
            ],
            'Украина'   => [
                'kie' => ['name' => 'Киев', 'gps' => '50.402411394193074,30.532690550000098'],
                'har' => ['name' => 'Харьков', 'gps' => '49.98734133297124,36.27310710000006'],
                'ode' => ['name' => 'Одесса', 'gps' => '46.46015314862733,30.711787500000014'],
                'dne' => ['name' => 'Днепропетровск', 'gps' => '48.46241037424381,35.00035649999995'],
                'don' => ['name' => 'Донецк', 'gps' => '47.99021347825789,37.76152060000004'],
                'zap' => ['name' => 'Запорожье', 'gps' => '47.85577294848013,35.175353549999954'],
                'lvo' => ['name' => 'Львов', 'gps' => '49.83273243784637,24.012235550000014'],
            ],
            'Эстония'   => ['tal' => ['name' => 'Таллин', 'gps' => '59.42503798655721,24.738241399999993']],
            'Латвия'    => ['rig' => ['name' => 'Рига', 'gps' => '56.9716502682317,24.129162500000007']],
            'Литва'     => ['vil' => ['name' => 'Вильнюс', 'gps' => '54.70038697008473,25.252932149999992']],
            'Казахстан' => ['alm' => ['name' => 'Алматы', 'gps' => '43.26517503624403,76.96622380000008'], 'ast' => ['name' => 'Астана', 'gps' => '51.15579680198851,71.47938970000007']],
            'Финляндия' => [
                'hel' => ['name' => 'Хельсинки', 'gps' => '60.169845018842516,24.93855080000003'],
                'esp' => ['name' => 'Эспоо', 'gps' => '60.20512302773431,24.65632000000005'],
                'tam' => ['name' => 'Тампере', 'gps' => '61.498150805616085,23.761025400000108'],
                'van' => ['name' => 'Вантаа', 'gps' => '60.293366417651754,25.037732900000037'],
                'tur' => ['name' => 'Турку', 'gps' => '60.43279893155271,22.225399500000094'],
                'row' => ['name' => 'Рованиеми', 'gps' => '66.49702159734223,25.724999000000025'],
                'kuo' => ['name' => 'Куопио', 'gps' => '62.893334690678806,27.679338500000085'],
                'lah' => ['name' => 'Лахти', 'gps' => '60.98369251085005,25.650316900000007'],
                'ima' => ['name' => 'Иматра', 'gps' => '61.19408280873665,28.776127599999995'],
                'lap' => ['name' => 'Лаппеенранта', 'gps' => '61.05494121013977,28.18962590000001'],
                'cot' => ['name' => 'Котка', 'gps' => '60.46662266596834,26.94599979999998'],
            ],
            'Швеция'    => ['sto' => ['name' => 'Стокгольм', 'gps' => '59.326294118560725,17.98754555000005'], 'get' => ['name' => 'Гётеборг', 'gps' => '57.702313143709354,11.893682500000068']],
            'Норвегия'  => ['osl' => ['name' => 'Осло', 'gps' => '59.89396160257896,10.785116549999998']],
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
                $vCity->setName($city['name']);
                $vCity->setGps($city['gps']);
                $vCity->setParent($vCountry);
                $vCity->setShortName($shortName ? $shortName : 'CODE');
                $manager->persist($vCity);

                $this->addReference("city[{$shortName}]", $vCity);
            }
            $manager->flush();
        }
    }

    public function getOrder()
    {
        return 15;
    }
}
