<?php
namespace Sto\CoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Sto\UserBundle\Entity\User;

class CalcUsersRatingCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('sto:calculate:rating:users')
            ->setDescription('Recalc users rating')
            ->addArgument(
                'name',
                InputArgument::OPTIONAL,
                'What user do you want to recalc rating?'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();

        $ratingPoint = $em->getRepository('StoCoreBundle:RatingPoints')->findAll();
        $ratePts = [];
        foreach ($ratingPoint as $row) {
            $ratePts[$row->getPointName()]= $row->getValue();
        }
        $ratingPointsRegistration = $ratePts['registration'];
        $ratingPointsGarage = $ratePts['carInGarage'];
        $ratingPointsSocialNetwork = $ratePts['linkToSocialNetwork'];
        $ratingPointsForums = $ratePts['linkToAutoForums'];
        $ratingPointsFeedback = $ratePts['ownReview'];
        $minFeedbacks = $ratePts['minFeedbacks'];
        $ratingPointsUseful = $ratePts['plusFeedbacks'];
        $ratingPointsUseless = $ratePts['minusFeedbacks'];
        $ratingPointsAnother = $ratePts['anotherReview'];

        $name = $input->getArgument('name');
        if (!$name)
            $users = $em->getRepository('StoUserBundle:User')->findAll();
        else
            $users = $em->getRepository('StoUserBundle:User')->findBy(['name'=>$name]);

        foreach ($users as $key=>$user) {
            $rating = $ratingPointsRegistration; // за регистрацию
            $output->write($key. ') ' . $user);
            // за добавление машины в гараж
            // за добавление в соцсеть +10
            // ВК
            if(($user->getLinkVK()!='') && ($user->getVkontakteId()!=''))
                $rating += $ratingPointsSocialNetwork;
            if($user->getLinkFB()=='')
                $rating += $ratingPointsSocialNetwork;
            if($user->getLinkGP()=='')
                $rating += $ratingPointsSocialNetwork;
            // за профиль в автофорум +10 * N
            $rating += $user->getAutoProfilesLinksCount()*$ratingPointsForums;
            // за отзыв
            if (($feedbacks = $em->getRepository('StoCoreBundle:Feedback')->findBy(['userId'=>$user->getId()]))!==null) {
                $rating += count($feedbacks)*$ratingPointsFeedback;
                // отзывов должно быть не меньше, чем столько
                if (count($feedbacks) >= $minFeedbacks) {
                    foreach ($feedbacks as $feedback) {
                    // за полезность отзыва +2
                        $rating += $feedback->getPluses()*$ratingPointsUseful;
                    // за бесполезность отзыва
                        $rating += $feedback->getMinuses()*$ratingPointsUseless;
                    }
                }
            }
            // Another feedbacks
            $output->writeln(" -> " . $rating);
            $user->setRating($rating);
            $em->persist($user);
        }
        $em->flush();
    }
}
