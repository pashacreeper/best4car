<?php
namespace Sto\CoreBundle\DataFixtures\ORM\Settings;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use sto\CoreBundle\Entity\Company;
use Sto\CoreBundle\Form\ChoiceList\EmailTemplateType;
use Sto\CoreBundle\Entity\EmailTemplate;

class LoadEmailTemplates extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $template = new EmailTemplate();
        $template->setTitle('Регистрация пользователя');
        $template->setContent('%user% успешно зарегестрирован');
        $template->setType(EmailTemplateType::TEMPLATE_REGISTRATION);
        $manager->persist($template);

        $template = new EmailTemplate();
        $template->setTitle('Восстановление пароля');
        $template->setContent('%user% %link%');
        $template->setType(EmailTemplateType::TEMPLATE_RESETING);
        $manager->persist($template);

        $template = new EmailTemplate();
        $template->setTitle('Ответ на ваш коментарий');
        $template->setContent('%user% %link%');
        $template->setType(EmailTemplateType::TEMPLATE_FEEDBACK_ANSWER_COMPANY);
        $manager->persist($template);

        $template = new EmailTemplate();
        $template->setTitle('Ответ на ваш коментарий');
        $template->setContent('%user% %link%');
        $template->setType(EmailTemplateType::TEMPLATE_FEEDBACK_ANSWER_DEAL);
        $manager->persist($template);

        $template = new EmailTemplate();
        $template->setTitle('Зарегестрирована компания');
        $template->setContent('%user% %company% %link%');
        $template->setType(EmailTemplateType::TEMPLATE_MANAGER_COMPANY_REGISTER);
        $manager->persist($template);

        $template = new EmailTemplate();
        $template->setTitle('Оставлен коментарий к компании');
        $template->setContent('%user% %company% %link%');
        $template->setType(EmailTemplateType::TEMPLATE_MANAGER_COMPANY_FEEDBACK);
        $manager->persist($template);

        $template = new EmailTemplate();
        $template->setTitle('Оставлен коментарий к акции компании');
        $template->setContent('%user% %company% %deal% %link%');
        $template->setType(EmailTemplateType::TEMPLATE_MANAGER_DEAL_FEEDBACK);
        $manager->persist($template);

        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }
}
