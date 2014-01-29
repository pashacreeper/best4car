<?php
namespace Sto\CoreBundle\Service;

use Doctrine\ORM\EntityManager;
use Sto\UserBundle\Entity\User;
use Sto\CoreBundle\Service\EmailTemplateTransformer;

class EmailNotifications
{
    /**
     * @var Swift_Mailer
     */
    protected $mailer;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var EmailTemplateTransformer
     */
    protected $transformer;

    protected $emailTemplateRepository;

    public function __construct(Swift_Mailer $mailer, EntityManager $em, EmailTemplateTransformer $transformer)
    {
        $this->mailer = $mailer;
        $this->em = $em;
        $this->transformer = $transformer;

        $this->emailTemplateRepository = $em->getRepository('StoCoreBundle:EmailTemplates');
    }

    private function getEmailTemplate($type)
    {
        $this->emailTemplateRepository->findOneByType($type);
    }

    private function send($email, $subject, $message)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom('noreply@best4car.ru')
            ->setTo($email)
            ->setBody($message);

        return $this->mailer->send($message);
    }

    public function sendRegistrationEmail(User $user)
    {
        $tempalte = $this->getEmailTemplate(EmailTemplateType::TEMPLATE_REGISTRATION);

        return $this->send(
            $user->getEmail(),
            $tempalte->getTitle(),
            $this->transformer->transform($tempalte->getContent(), $user)
        );
    }

    public function sendResetingEmail(User $user)
    {
        $tempalte = $this->getEmailTemplate(EmailTemplateType::TEMPLATE_RESETING);

        return $this->send(
            $user->getEmail(),
            $tempalte->getTitle(),
            $this->transformer->transform($tempalte->getContent(), $user)
        );
    }

    public function sendFeedbackAnswerEmail(User $user)
    {
        $tempalte = $this->getEmailTemplate(EmailTemplateType::TEMPLATE_FEEDBACK_ANSWER);

        return $this->send(
            $user->getEmail(),
            $tempalte->getTitle(),
            $this->transformer->transform($tempalte->getContent(), $user)
        );
    }

    public function sendCompanyRegisterEmail(Company $company)
    {
        $managers = $company->getCompanyManager();
        $tempalte = $this->getEmailTemplate(EmailTemplateType::TEMPLATE_MANAGER_COMPANY_REGISTER);

        foreach ($managers as $manager) {
            $this->send(
                $manager->getEmail(),
                $tempalte->getTitle(),
                $this->transformer->transform($tempalte->getContent(), $manager, $company)
            );
        }
    }

    public function sendCompanyFeedbackEmail(Company $company)
    {
        $managers = $company->getCompanyManager();
        $tempalte = $this->getEmailTemplate(EmailTemplateType::TEMPLATE_MANAGER_COMPANY_FEEDBACK);

        foreach ($managers as $manager) {
            $this->send(
                $manager->getEmail(),
                $tempalte->getTitle(),
                $this->transformer->transform($tempalte->getContent(), $manager, $company)
            );
        }
    }

    public function sendCompanyDealFeedbackEmail(Company $company)
    {
        $managers = $company->getCompanyManager();
        $tempalte = $this->getEmailTemplate(EmailTemplateType::TEMPLATE_MANAGER_DEAL_FEEDBACK);

        foreach ($managers as $manager) {
            $this->send(
                $manager->getEmail(),
                $tempalte->getTitle(),
                $this->transformer->transform($tempalte->getContent(), $manager, $company)
            );
        }
    }
}
