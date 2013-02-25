<?php

namespace Sto\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

use sto\CoreBundle\Entity\Feedback;
use sto\CoreBundle\Entity\FeedbackAnswer;

class LoadFeedbackData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {

        $user = $manager->getRepository('StoUserBundle:User')->find(1);


        $feedback = new Feedback();
        $feedback->setContent('Был приятно удивлен качеством обслуживания, девушки улыбались, были приветливы, мастер профессионален и учтив. Во общем почувствовал себя долгожданным гостем. Всё сделали быстро и качественно. всем советую.');
        $feedback->setVisitDate(new \DateTime("now"));
        $feedback->setMastername('Поликнинов И.П.');
        $feedback->setCar('Mazda 3');
        $feedback->setGn('А099АА 178');
        $feedback->setNn('З/Н № 121212');
        $feedback->setComapnyRating(4);
        $feedback->setFeedbackRating(0.5);
        $feedback->setPluses('');
        $feedback->setMinuses('');
        $feedback->setTargetRating('СТО');
        $feedback->setIsPublished(true);
        $feedback->setUser($user);

        $manager->persist($feedback);
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
