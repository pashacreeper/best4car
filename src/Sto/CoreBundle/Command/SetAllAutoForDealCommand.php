<?php
namespace Sto\CoreBundle\Command;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Sto\CoreBundle\Entity\Company;

class SetAllAutoForDealCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('sto:deal:allauto')
            ->setDescription('Set all auto flag for deals')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var $em EntityManager */
        $em = $this->getContainer()->get('doctrine')->getManager();
        $deals = $em->getRepository('StoCoreBundle:Deal')->findAll();

        /** @var $company Deal */
        foreach ($deals as $deal) {
            if ($deal->getAuto()->count() === 0) {
                $deal->setAllAuto(true);
            } else {
                $deal->setAllAuto(false);
            }
        }
        $em->flush();
    }
}
