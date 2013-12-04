<?php
namespace Sto\CoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GetFirstNameFromVKCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('sto:user:get_user_names_from_vk')
            ->setDescription('Geting first and last name fro mvk for users which dont have them');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();

        $users = $em->getRepository('StoUserBundle:User')->findBy([
            'firstName' => ''
        ]);

        foreach ($users as $user) {
            if ($vkId = $user->getVkontakteId()) {
                $url = "https://api.vk.com/method/users.get?fields=nickname&user_ids={$vkId}";
                $additionalData = json_decode(file_get_contents($url));
                $data = $additionalData->response[0];

                $user->setFirstName($data->first_name);
                if (! $user->getLastName()) {
                    $user->setLastName($data->last_name);
                }

                $em->persist($user);
            }
        }

        $em->flush();
    }
}
