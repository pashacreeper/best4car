<?php
namespace Sto\CoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Sto\CoreBundle\Entity\CompanyEmail;

class MoveEmailsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('sto:company:move_email')
            ->setDescription('Move emails from company entity to separete entity');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();

        $companies = $em->getRepository('StoCoreBundle:Company')->findAll();

        foreach ($companies as $company) {
            $email = $company->getEmail();
            if ($email) {
                $companyEmail = new CompanyEmail();
                $companyEmail->setEmail($email);
                $companyEmail->setDescription('Основной');
                $companyEmail->setCompany($company);

                $em->persist($companyEmail);
            }
        }

        $em->flush();
    }
}
