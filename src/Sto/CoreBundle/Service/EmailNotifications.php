<?php
namespace Sto\CoreBundle\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Sto\CoreBundle\Entity\Company;
use Sto\CoreBundle\Form\ChoiceList\EmailTemplateType;
use Sto\UserBundle\Entity\User;
use Sto\CoreBundle\Service\EmailTemplateTransformer;
use Swift_Mailer;
use Symfony\Component\Routing\RouterInterface;

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

    /**
     * @var RouterInterface
     */
    protected $router;

    /**
     * @var EntityRepository
     */
    protected $emailTemplateRepository;

    public function __construct(Swift_Mailer $mailer, EntityManager $em, EmailTemplateTransformer $transformer, RouterInterface $router)
    {
        $this->mailer = $mailer;
        $this->em = $em;
        $this->transformer = $transformer;
        $this->router = $router;

        $this->emailTemplateRepository = $em->getRepository('StoCoreBundle:EmailTemplate');
    }

    /**
     * @param string $type
     *
     * @return mixed
     */
    private function getEmailTemplate($type)
    {
        return $this->emailTemplateRepository->findOneBy(['type' => $type]);
    }

    /**
     * @param $email
     * @param $subject
     * @param $message
     *
     * @return int
     */
    private function send($email, $subject, $message)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom('no-reply@best4car.ru')
            ->setTo($email)
            ->setBody($message);

        return $this->mailer->send($message);
    }

    /**
     * Sending user successful registered email
     * @param User $user
     *
     * @return mixed
     */
    public function sendRegistrationEmail(User $user)
    {
        $template = $this->getEmailTemplate(EmailTemplateType::TEMPLATE_REGISTRATION);

        return $this->send(
            $user->getEmail(),
            $template->getTitle(),
            $this->transformer->transform($template->getContent(), ['user' => $user])
        );
    }

    /**
     * @param User $user
     *
     * @return int
     */
    public function sendResettingEmail(User $user)
    {
        $template = $this->getEmailTemplate(EmailTemplateType::TEMPLATE_RESETING);

        return $this->send(
            $user->getEmail(),
            $template->getTitle(),
            $this->transformer->transform($template->getContent(), [
                'user' => $user,
                'link' => $this->router->generate('fos_user_registration_confirm', ['token' => $user->getConfirmationToken()], true)
            ])
        );
    }

    public function sendFeedbackAnswerEmail(User $user)
    {
        $template = $this->getEmailTemplate(EmailTemplateType::TEMPLATE_FEEDBACK_ANSWER);

        return $this->send(
            $user->getEmail(),
            $template->getTitle(),
            $this->transformer->transform($template->getContent(), $user)
        );
    }

    public function sendCompanyRegisterEmail(Company $company)
    {
        $managers = $company->getCompanyManager();
        $template = $this->getEmailTemplate(EmailTemplateType::TEMPLATE_MANAGER_COMPANY_REGISTER);

        foreach ($managers as $manager) {
            $this->send(
                $manager->getEmail(),
                $template->getTitle(),
                $this->transformer->transform($template->getContent(), $manager, $company)
            );
        }
    }

    public function sendCompanyFeedbackEmail(Company $company)
    {
        $managers = $company->getCompanyManager();
        $template = $this->getEmailTemplate(EmailTemplateType::TEMPLATE_MANAGER_COMPANY_FEEDBACK);

        foreach ($managers as $manager) {
            $this->send(
                $manager->getEmail(),
                $template->getTitle(),
                $this->transformer->transform($template->getContent(), $manager, $company)
            );
        }
    }

    public function sendCompanyDealFeedbackEmail(Company $company)
    {
        $managers = $company->getCompanyManager();
        $template = $this->getEmailTemplate(EmailTemplateType::TEMPLATE_MANAGER_DEAL_FEEDBACK);

        foreach ($managers as $manager) {
            $this->send(
                $manager->getEmail(),
                $template->getTitle(),
                $this->transformer->transform($template->getContent(), $manager, $company)
            );
        }
    }
}
