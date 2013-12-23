<?php
namespace Sto\CoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Sto\CoreBundle\Entity\CompanyPhone;

class MovePhonesCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('sto:company:move_phone')
            ->setDescription('Move phones from company entity to separete entity');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();

        $companies = $em->getRepository('StoCoreBundle:Company')->findAll();

        foreach ($companies as $company) {
            $phones = $company->getPhones();
            if ($phones) {
                foreach ($phones as $phone) {
                    $companyPhone = new CompanyPhone();
                    $companyPhone->setPhone($phone['phone']);
                    $companyPhone->setDescription($phone['description']);
                    $companyPhone->setCompany($company);

                    $em->persist($companyPhone);
                }

            }
        }

        $em->flush();
    }
}
