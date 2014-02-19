<?php
namespace Sto\CoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Sto\UserBundle\Entity\Group;

class FillGroupsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('sto:fill:groups')
            ->setDescription('Set user group where groups is empty');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();

        $users = $em->getRepository('StoUserBundle:User')->findAll();

        $userGroup = $em->getRepository('StoUserBundle:Group')->find(Group::USER);

        $n = 0;
        foreach ($users as $user) {
            if (count($user->getGroups()) === 0) {
                $n++;
                $user->setGroups([$userGroup]);
            }
        }

        $em->flush();

        $output->writeln(sprintf("Updated users: %d", $n));
    }
}
