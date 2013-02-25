<?php

namespace Sto\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface,
    Doctrine\Common\DataFixtures\AbstractFixture,
    Doctrine\Common\Persistence\ObjectManager;
use Sto\CoreBundle\Entity\Feedback,
    Sto\CoreBundle\Entity\FeedbackAnswer;

class LoadFeedbackData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $user = $manager->getRepository('StoUserBundle:User')->find(1);
        $lastNames = ['Смирнов','Иванов','Кузнецов','Попов','Соколов'];

        for ($i=1; $i < 11; $i++) {
            $feedback = new Feedback;
            $feedback->setContent('Был приятно удивлен качеством обслуживания, девушки улыбались, были приветливы, мастер профессионален и учтив. Во общем почувствовал себя долгожданным гостем. Всё сделали быстро и качественно. всем советую.');
            $feedback->setVisitDate(new \DateTime("now"));
            $feedback->setMastername($lastNames[rand(0,4)] . ' И.П.');
            $feedback->setCar('Mazda 3');
            $feedback->setGn('А0' . rand(12,98) . 'АА ' . rand(123, 987));
            $feedback->setNn('З/Н № ' . rand(123456, 987654));
            $feedback->setComapnyRating(rand(1,5));
            $feedback->setFeedbackRating(0.5);
            $feedback->setPluses('');
            $feedback->setMinuses('');
            $feedback->setTargetRating('СТО');
            $feedback->setIsPublished(true);
            $feedback->setUser($user);

            $manager->persist($feedback);
        }

        $manager->flush();

        $user = $manager->getRepository('StoUserBundle:User')->find(2);
        $feedback_answer = new FeedbackAnswer();
        $feedback_answer->setAnswer('Спасибо за Ваш отзыв! Будем рады видеть Вас снова!');
        $feedback_answer->setDate(new \DateTime("now"));
        $feedback_answer->setManager($user);
        $feedback_answer->setFeedback($feedback);
        $manager->persist($feedback_answer);
        $manager->flush();
    }

    public function getOrder()
    {
        return 3;
    }
}
