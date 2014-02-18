<?php
namespace Sto\CoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Sto\CoreBundle\Entity\CompanyEmail;

class RepairSpecializationsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('sto:repair:specializations')
            ->setDescription('Repair specializations in companies');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();

        $companies = $em->getRepository('StoCoreBundle:Company')->findAll();

        $wrongCompanies = [];

        foreach ($companies as $company) {
            foreach ($company->getSpecializations() as $spec) {
                foreach ($spec->getServices() as $service) {
                    if ($service->getService()->getCompanyType() != $spec->getType()) {
                        $em->remove($service);
                        $wrongCompanies[] = $company->getId();
                    }
                }
            }
        }

        $wrongCompanies = array_unique($wrongCompanies);

        $output->writeln("Next companies have wrong services: ".implode(", ", $wrongCompanies));

        $em->flush();
    }
}
