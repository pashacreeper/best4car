<?php
namespace Sto\CoreBundle\Command;

use Doctrine\ORM\EntityManager;
use Sto\CoreBundle\Entity\FeedbackCompany;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Sto\CoreBundle\Entity\Company;
use Sto\UserBundle\Entity\User;

class CalcFeedbackNumberCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('sto:calculate:feedback:number')
            ->setDescription('Calc feedback number')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var $em EntityManager */
        $em = $this->getContainer()->get('doctrine')->getManager();
        
        $companies = $em->getRepository('StoCoreBundle:Company')->findAll();

        foreach ($companies as $company) {
            $feedbacks = $em->getRepository('StoCoreBundle:FeedbackCompany')->findBy(array('company' => $company), array('id' => 'ASC'));
            $n = 0;
            foreach ($feedbacks as $feedback) {
                $n++;
                $feedback->setFeedbackNumber($n);
            }
        }

        $deals = $em->getRepository('StoCoreBundle:Deal')->findAll();

        foreach ($deals as $deal) {
            $feedbacks = $em->getRepository('StoCoreBundle:FeedbackDeal')->findBy(array('deal' => $deal), array('id' => 'ASC'));
            $n = 0;
            foreach ($feedbacks as $feedback) {
                $n++;
                $feedback->setFeedbackNumber($n);
            }
        }

        $em->flush();
    }
}
