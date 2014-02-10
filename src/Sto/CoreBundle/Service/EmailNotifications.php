<?php
namespace Sto\CoreBundle\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Sto\CoreBundle\Entity\Company;
use Sto\CoreBundle\Entity\CompanyManager;
use Sto\CoreBundle\Entity\Deal;
use Sto\CoreBundle\Entity\Feedback;
use Sto\CoreBundle\Entity\FeedbackCompany;
use Sto\CoreBundle\Entity\FeedbackDeal;
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
            ->setFrom(['noreply@best4car.ru' => 'best4car.ru'])
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

    /**
     * @param User                            $user
     * @param \Sto\CoreBundle\Entity\Feedback $feedback
     *
     * @return int
     */
    public function sendFeedbackAnswerEmail(User $user, Feedback $feedback)
    {
        if ($feedback instanceof FeedbackCompany) {
            $this->sendFeedbackCompanyAnswerEmail($feedback, $user, $this->getEmailTemplate(EmailTemplateType::TEMPLATE_FEEDBACK_ANSWER_COMPANY));
        } elseif ($feedback instanceof FeedbackDeal) {
            $this->sendFeedbackDealAnswerEmail($feedback, $user, $this->getEmailTemplate(EmailTemplateType::TEMPLATE_FEEDBACK_ANSWER_DEAL));
        }
    }

    private function sendFeedbackCompanyAnswerEmail(FeedbackCompany $feedback, User $user, $template)
    {
        $link = $this->router->generate('content_company_show', ['id' => $feedback->getCompany()->getId()], true) . '#feedbacks';

        return $this->send(
            $user->getEmail(),
            $template->getTitle(),
            $this->transformer->transform($template->getContent(), [
                'user' => $user,
                'link' => $link,
                'company' => $feedback->getCompany()
            ])
        );
    }

    private function sendFeedbackDealAnswerEmail(FeedbackDeal $feedback, User $user, $template)
    {
        $link = $this->router->generate('content_deal_show', ['id' => $feedback->getDeal()->getId()], true) . '#feedbacks';

        return $this->send(
            $user->getEmail(),
            $template->getTitle(),
            $this->transformer->transform($template->getContent(), [
                'user' => $user,
                'link' => $link,
                'deal' => $feedback->getDeal()
            ])
        );
    }

    /**
     * @param Company $company
     */
    public function sendCompanyRegisterEmail(Company $company)
    {
        $managers = $company->getCompanyManager();
        $template = $this->getEmailTemplate(EmailTemplateType::TEMPLATE_MANAGER_COMPANY_REGISTER);

        /** @var CompanyManager $manager */
        foreach ($managers as $manager) {
            $this->send(
                $manager->getUser()->getEmail(),
                $template->getTitle(),
                $this->transformer->transform($template->getContent(), [
                    'user' => $manager->getUser(),
                    'company' => $company,
                    'link' => $this->router->generate('content_company_show', ['id' => $company->getId()], true)
                ])
            );
        }
    }

    /**
     * @param Company $company
     */
    public function sendCompanyFeedbackEmail(Company $company)
    {
        $managers = $company->getCompanyManager();
        $template = $this->getEmailTemplate(EmailTemplateType::TEMPLATE_MANAGER_COMPANY_FEEDBACK);

        /** @var CompanyManager $manager */
        foreach ($managers as $manager) {
            $this->send(
                $manager->getUser()->getEmail(),
                $template->getTitle(),
                $this->transformer->transform($template->getContent(), [
                    'user' => $manager->getUser(),
                    'company' => $company,
                    'link' => $this->router->generate('content_company_show', ['id' => $company->getId()], true) . '#feedbacks'
                ])
            );
        }
    }

    public function sendCompanyDealFeedbackEmail(Company $company, Deal $deal)
    {
        $managers = $company->getCompanyManager();
        $template = $this->getEmailTemplate(EmailTemplateType::TEMPLATE_MANAGER_DEAL_FEEDBACK);

        /** @var CompanyManager $manager */
        foreach ($managers as $manager) {
            $this->send(
                $manager->getUser()->getEmail(),
                $template->getTitle(),
                $this->transformer->transform($template->getContent(), [
                    'user' => $manager->getUser(),
                    'company' => $company,
                    'deal' => $deal,
                    'link' => $this->router->generate('content_deal_show', ['id' => $deal->getId()], true) . '#feedbacks'
                ])
            );
        }
    }
}
