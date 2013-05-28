<?php

namespace Sto\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Sto\CoreBundle\Entity\RatingPoints;

class LoadRatingPointsData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $ratingPointsData = [
            ['registration','Регистрация на сайте', 10],
            ['carInGarage','Добавление машины в гараж', 20],
            ['linkToSocialNetwork','Добавление ссылок на профили в соц.сетях', 10],
            ['linkToAutoForums','Добавление ссылок на профили на автофорумах', 10],
            ['ownReview','Добавление своего отзыва', 5],
            ['minFeedbacks','Расчет коэффициента полезности отзыва после количества оценок', 10],
            ['plusFeedbacks','За полезность отзыва', 2],
            ['minusFeedbacks','За бесполезность отзыва', -5],
            ['anotherReview','Оценка чужого отзыва', 0.1],
        ]
        ;

        foreach ($ratingPointsData as $row) {
            $ratingPoints = (new RatingPoints)
                ->setPointName($row[0])
                ->setDescription($row[1])
                ->setValue($row[2])
            ;

            $manager->persist($ratingPoints);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 19;
    }
}
