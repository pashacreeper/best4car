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

class FillFeedCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('sto:feed:fill')
            ->setDescription('Create feed entries from deals and companies')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var $em EntityManager */
        $em = $this->getContainer()->get('doctrine')->getManager();
        $fm = $this->getContainer()->get('sto.manager.feed');

        $companies = $em->getRepository('StoCoreBundle:Company')->findBy(['registredFully'=>true]);

        foreach ($companies as $company) {
             $fm->createOnItem($company, false);
        }

        $deals = $em->getRepository('StoCoreBundle:Deal')->findAll();

        foreach ($deals as $deal) {
             $fm->createOnItem($deal, false);
        }
    }
}
