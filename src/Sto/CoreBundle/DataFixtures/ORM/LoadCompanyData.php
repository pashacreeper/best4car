<?php

namespace Sto\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

use sto\CoreBundle\Entity\Company;

class LoadCompanyData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $phone_type = ['Рабочий', 'Факс'];

        $company = new Company;
        $company->setName('Автопродикс');
        $company->setSlogan('ВЕСЬ НИССАН ЗА ОДИН ВИЗИТ!');
        $company->setFullName('Группа компаний “Автопродикс”');
        $company->setWeb('http://infiniti.autoprodix.ru');
        for ($k=1; $k < rand(3,5); $k++) {
            $company->addSpecialization($this->getReference("companiesTypesParent[{$k}]"));
        }
        for ($k=1; $k < rand(3,5); $k++) {
            $company->addServices($this->getReference("companiesTypesChildren[{$k}]"));
        }
        for ($k=0; $k < rand(3,14); $k++) {
            $company->addAdditionalServices($this->getReference("additionalService[{$k}]"));
        }
        chdir(__DIR__ . '/../../../../../');
        $from = "app/Resources/fixtures/company/".rand(1,42).".png";
        $to = "web/storage/images/company_logo/1.png";

        if (!file_exists($from))
            $from = "app/Resources/fixtures/company/1.png";

        if (!is_dir(dirname($to))) {
            mkdir(dirname($to), 0755, true);
        }

        if (!file_exists($to)) {
            copy($from, $to);
        }
        $company->setLogoName('1.png');
        $company->setWorkingTime([
                [
                    'from' => new \DateTime(rand(8,12).':00:00'),
                    'till' => new \DateTime(rand(15,20).':00:00'),
                    'dayFrom' => 'Пн',
                    'dayTill' => 'Вт'
                ],
                [
                    'from' => new \DateTime(rand(8,12).':00:00'),
                    'till' => new \DateTime(rand(15,20).':00:00'),
                    'dayFrom' => 'Сб',
                    'dayTill' => 'Вс'
                ]
            ]);
        $company->setPhones([
                [
                    'phone' => '+2 (' . rand(123, 987) .') ' . rand(123, 987) . '-' . rand(12, 98). '-' . rand(12, 98),
                    'description' => $phone_type[rand(0,1)],
                ],
                [
                    'phone' => '+2 (' . rand(123, 987) .') ' . rand(123, 987) . '-' . rand(12, 98). '-' . rand(12, 98),
                    'description' => $phone_type[rand(0,1)],
                ]
        ]);
        $company->setSkype('altauto');
        $company->setEmail('infiniti-info@autoprodix.ru');
        $company->setAddress('197374, Санкт-Петербург, ул. Школьная д. 71 корпус 3');
        $company->setGps('59.99, 30.21');
        $company->setCreatetDate(new \DateTime('now'));
        $company->setPhotos('Photos');
        $company->setSocialNetworks('http://vk.com/infinitiauto');
        $company->setRating(rand(500,600));
        $company->setReviews('1,2,3,4,5');
        $company->setDescription('Группа компаний «Автопродикс» является официальным дилером и представляет весь модельный ряд легковых и грузопассажирских автомобилей Nissan, Renault и Infiniti. Компания «Автопродикс» уже более 17 лет на автомобильном рынке, является самым старейшим и крупнейшим в Санкт-Петерурге дилером. Автопродикс входит в тройку самых крупных автодилеров России и предлагает своим клиентам полный спектр услуг: продажа автомобилей в кредит и лизинг, автострахование, высокий уровень сервиса. В нашем автоцентре квалифицированный персонал поможет Вам выбрать автомобиль, идеально отвечающий Вашим требованиям и впоследствии получать качественное и высокопрофессиональное обслуживание. Таким образом, наша миссия - предоставить свободу выбора и максимально возможный комфорт и свободу выбора.
Группа компаний «Автопродикс» является крупнейшим официальным дилером Nissan и единственным официальным дилером Infiniti в Северо-Западном регионе.');
        $company->setSubscribable('Lorem ipsum dolor sit amet, consectetur adipisicing elit.');
        $company->setHourPrice(rand(500,4500));
        $company->setCurrency($this->getReference('currencies['.rand(0,9).']'));

        //for($i = 0; $i <= rand(1,2); $i++)
            //$company->addManager($this->getReference("user[".rand(1,$i*15)."]"));

        $company->setAdministratorContactInfo('8 (921) 313-67-14, Константин');
        $company->setVisible(true);
        $company->setNotes('Lorem ipsum dolor sit amet, consectetur adipisicing elit.');
        $company->setGroups([$this->getReference("groups[".rand(0,5)."]")]);
        $company->setRatingGroup($this->getReference("rating_groups[2]"));
        $manager->persist($company);
        $this->addReference("company[1]", $company);

        $company = new Company;
        $company->setName('ДИЛИЖАНС');
        $company->setSlogan('Контакты с компанией «Дилижанс» должны изменять представление клиентов об автосервисе, как об отрасли в целом, на позитивное');
        $company->setFullName('Автосервис «ДИЛИЖАНС»');
        $company->setWeb('http://www.dilauto.ru');
        for ($k=1; $k < rand(3,5); $k++) {
            $company->addSpecialization($this->getReference("companiesTypesParent[{$k}]"));
        }
        for ($k=1; $k < rand(3,5); $k++) {
            $company->addServices($this->getReference("companiesTypesChildren[{$k}]"));
        }
        for ($k=0; $k < rand(3,14); $k++) {
            $company->addAdditionalServices($this->getReference("additionalService[{$k}]"));
        }

        chdir(__DIR__ . '/../../../../../');
        $from = "app/Resources/fixtures/company/".rand(1,42).".png";
        $to = "web/storage/images/company_logo/2.png";

        if (!file_exists($from))
            $from = "app/Resources/fixtures/company/2.png";

        if (!is_dir(dirname($to))) {
            mkdir(dirname($to), 0755, true);
        }

        if (!file_exists($to)) {
            copy($from, $to);
        }
        $company->setLogoName('2.png');
        $company->setWorkingTime([
                [
                    'from' => new \DateTime(rand(8,12).':00:00'),
                    'till' => new \DateTime(rand(15,20).':00:00'),
                    'dayFrom' => 'Пн',
                    'dayTill' => 'Вт'
                ],
                [
                    'from' => new \DateTime(rand(8,12).':00:00'),
                    'till' => new \DateTime(rand(15,20).':00:00'),
                    'dayFrom' => 'Сб',
                    'dayTill' => 'Вс'
                ]
            ]);
        $company->setPhones([
                [
                    'phone' => '+2 (' . rand(123, 987) .') ' . rand(123, 987) . '-' . rand(12, 98). '-' . rand(12, 98),
                    'description' => $phone_type[rand(0,1)],
                ],
                [
                    'phone' => '+2 (' . rand(123, 987) .') ' . rand(123, 987) . '-' . rand(12, 98). '-' . rand(12, 98),
                    'description' => $phone_type[rand(0,1)],
                ]
        ]);
        $company->setSkype('altauto');
        $company->setEmail('infiniti-info@autoprodix.ru');
        $company->setAddress('Санкт-Петербург, пр. Елизарова, д. 34');
        $company->setGps('59.89, 30.40');
        $company->setCreatetDate(new \DateTime('now'));
        $company->setPhotos('Photos');
        $company->setSocialNetworks('http://vk.com/diligens');
        $company->setRating(rand(500,600));
        $company->setReviews('1,2,3,4,5');
        $company->setDescription('Благодаря узкой специализации на марках Volkswagen, Audi, SKODA и SEAT (концернVAG), наша СТО имеет хорошее техническое оснащение специализированным немецким оборудованием для диагностики и ремонта именно Вашего автомобиля, и можете быть уверенны в том, что Вы не потратите лишнего времени из-за отсутствия у специалиста инструмента, необходимого для ремонта.');
        $company->setSubscribable('Lorem ipsum dolor sit amet, consectetur adipisicing elit.');
        $company->setHourPrice(rand(500,4500));
        $company->setCurrency($this->getReference('currencies['.rand(0,9).']'));
        //for($i = 0; $i <= 2; $i++)
            //$company->addManager($this->getReference("user[".rand(1,$i*15)."]"));
        $company->setAdministratorContactInfo('8 (921) 313-67-14, Константин');
        $company->setVisible(true);
        $company->setNotes('Lorem ipsum dolor sit amet, consectetur adipisicing elit.');
        $company->setGroups([$this->getReference("groups[".rand(0,5)."]")]);
        $company->setRatingGroup($this->getReference("rating_groups[2]"));
        $manager->persist($company);
        $this->addReference("company[2]", $company);

        for ($i=3; $i < 39 ; $i++) {
            $rating = rand(10, 600);
            if ($rating<100)
                $rating_group_id = 0;
            elseif ($rating>499)
                $rating_group_id = 2;
            else
                $rating_group_id = 1;

            $company = new Company;
            $company->setName('Test company - ' . $i);
            $company->setSlogan('Slogan - ' . $i);
            $company->setFullName('Full name test company - ' . $i);
            $company->setWeb('http://www.test' . $i .'.ru');
            for ($k=1; $k < rand(3,5); $k++) {
                $company->addSpecialization($this->getReference("companiesTypesParent[{$k}]"));
            }
            for ($k=1; $k < rand(3,5); $k++) {
                $company->addServices($this->getReference("companiesTypesChildren[{$k}]"));
            }
            for ($k=0; $k < rand(3,14); $k++) {
                $company->addAdditionalServices($this->getReference("additionalService[{$k}]"));
            }

            chdir(__DIR__ . '/../../../../../');
            $from = "app/Resources/fixtures/company/".rand(1,42).".png";
            $to = "web/storage/images/company_logo/". $i .".png";

            if (!file_exists($from))
                $from = "app/Resources/fixtures/company/2.png";

            if (!is_dir(dirname($to))) {
                mkdir(dirname($to), 0755, true);
            }

            if (!file_exists($to)) {
                copy($from, $to);
            }

            $company->setLogoName($i . '.png');
            $company->setWorkingTime([
                [
                    'from' => new \DateTime(rand(8,12).':00:00'),
                    'till' => new \DateTime(rand(15,20).':00:00'),
                    'dayFrom' => 'Пн',
                    'dayTill' => 'Вт'
                ],
                [
                    'from' => new \DateTime(rand(8,12).':00:00'),
                    'till' => new \DateTime(rand(15,20).':00:00'),
                    'dayFrom' => 'Сб',
                    'dayTill' => 'Вс'
                ]
            ]);
            $company->setPhones([
                [
                    'phone' => '+2 (' . rand(123, 987) .') ' . rand(123, 987) . '-' . rand(12, 98). '-' . rand(12, 98),
                    'description' => $phone_type[rand(0,1)],
                ],
                [
                    'phone' => '+2 (' . rand(123, 987) .') ' . rand(123, 987) . '-' . rand(12, 98). '-' . rand(12, 98),
                    'description' => $phone_type[rand(0,1)],
                ]
            ]);
            $company->setSkype('altauto');
            $company->setEmail('info@altauto.ru');
            $company->setAddress('Lorem ipsum dolor sit amet, consectetur adipisicing elit.');
            $company->setGps( 59 . '.' . rand(85,98) . ', ' . 30 . '.' . rand(20,53) );
            $company->setCreatetDate(new \DateTime(rand(1, 28) . '-' . rand(1, 12) . '-' . rand(2011, 2020)));
            $company->setPhotos('Photos');
            $company->setSocialNetworks('Facebook, Vk, Google+');
            $company->setRating($rating);
            $company->setReviews('1,2,3,4,5');
            $company->setDescription('Lorem ipsum dolor sit amet, consectetur adipisicing elit.');
            $company->setSubscribable('Lorem ipsum dolor sit amet, consectetur adipisicing elit.');
            $company->setHourPrice(rand(500,4500));
            $company->setCurrency($this->getReference('currencies['.rand(0,9).']'));
            //for($k = 1; $k <= 2; $k++)
                //$company->addManager($this->getReference("user[".rand(1, $k*15)."]"));
            $company->setAdministratorContactInfo('Lorem ipsum dolor sit amet, consectetur adipisicing elit.');
            $company->setVisible(rand(0, 1));
            $company->setNotes('Lorem ipsum dolor sit amet, consectetur adipisicing elit.');
            $company->setGroups([$this->getReference("groups[".rand(0,5)."]")]);
            $company->setRatingGroup($this->getReference("rating_groups[".$rating_group_id."]"));
            $manager->persist($company);
            $this->addReference("company[{$i}]", $company);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 31;
    }
}
