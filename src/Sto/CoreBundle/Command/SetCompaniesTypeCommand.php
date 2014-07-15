<?php
namespace Sto\CoreBundle\Command;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Sto\CoreBundle\Entity\Company;

class SetCompaniesTypeCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('sto:set:companies:type')
            ->setDescription('Set companies type')
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
            $company->setTypeFromSpecs();
        }
        $em->flush();
    }
}
