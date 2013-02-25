<?php

namespace Sto\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface,
    Doctrine\Common\DataFixtures\AbstractFixture,
    Doctrine\Common\Persistence\ObjectManager;
use Sto\CoreBundle\Entity\FeedbackAnswer;

class LoadFeedbackAnswerData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for ($i=1; $i < 41; $i++) {
            $feedbackAnswer = new FeedbackAnswer;
            $feedbackAnswer->setAnswer('Спасибо за Ваш отзыв! Будем рады видеть Вас снова!');
            $feedbackAnswer->setDate(new \DateTime("now"));
            $feedbackAnswer->setManager($this->getReference("user[" . rand(1,30) . "]"));
            $feedbackAnswer->setFeedback($this->getReference("feedback[" . $i . "]"));
            $manager->persist($feedbackAnswer);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 6;
    }
}
