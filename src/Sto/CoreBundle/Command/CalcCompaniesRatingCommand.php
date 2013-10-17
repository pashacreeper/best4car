<?php
namespace Sto\CoreBundle\Command;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Sto\CoreBundle\Entity\Company;
use Sto\UserBundle\Entity\User;

class CalcCompaniesRatingCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('sto:calculate:rating:companies')
            ->setDescription('Recalc companies rating')
            ->addArgument(
                'name',
                InputArgument::OPTIONAL,
                'What company do you want to recalc rating?'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var $em EntityManager */
        $em = $this->getContainer()->get('doctrine')->getManager();
        $name = $input->getArgument('name');
        if (!$name) {
            $companies = $em->getRepository('StoCoreBundle:Company')->findAll();
        } else {
            $companies = $em->getRepository('StoCoreBundle:Company')->findBy(['name'=>$name]);
        }

        /** @var $company Company */
        foreach ($companies as $company) {
            $feedbacks = $company->getFeedbacks();
            $companyRating = null;
            if ($feedbacks->count() && $feedbacks->count() >= 5) {
                $numerator = 0.0;
                $denominator = 0.0;
                foreach ($feedbacks as $feedback) {
                    $user = $feedback->getUser();
                    $ratingGroup = $user->getRatingGroup();
                    $categoryMultiplier = $ratingGroup->getMultiplier();
                    $feedbackCompany = $em->getRepository('StoCoreBundle:FeedbackCompany')->findOneBy(
                        [
                            'user' => $user->getId(),
                            'company' => $company->getId()
                        ]
                    );
                    if ($feedbackCompany === null) {
                        $usersRatingToCompany = 1;
                    } else {
                        $usersRatingToCompany = $feedbackCompany->getFeedbackRating();
                    }
                    $trustMultiplier = ($feedback->getPluses()+$feedback->getMinuses() <> 0) ? $feedback->getPluses()/($feedback->getPluses()+$feedback->getMinuses()) : 1;

                    $numerator += $usersRatingToCompany * $categoryMultiplier * $trustMultiplier;
                    $denominator += $categoryMultiplier;
                }
                $companyRating = number_format($numerator / $denominator * 2.0, 1);
            }
            $company->setRating($companyRating);
            $em->persist($company);
            $output->writeln(
                sprintf("Company '%s', feedbacks %s, rating now is %s",
                    $company->getName(),
                    $feedbacks->count(),
                    $companyRating ? $companyRating : 'NULL'
                )
            );
        }
        $em->flush();
    }
}
