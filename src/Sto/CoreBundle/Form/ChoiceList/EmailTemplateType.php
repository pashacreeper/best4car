<?php
namespace Sto\CoreBundle\Form\ChoiceList;

use Symfony\Component\Form\Extension\Core\ChoiceList\SimpleChoiceList;

class EmailTemplateType extends SimpleChoiceList
{
    const TEMPLATE_REGISTRATION = 'template.registration';
    const TEMPLATE_RESETING = 'template.reseting';
    const TEMPLATE_FEEDBACK_ANSWER_COMPANY = 'template.feedback_answer.company';
    const TEMPLATE_FEEDBACK_ANSWER_DEAL = 'template.feedback_answer.deal';
    const TEMPLATE_MANAGER_COMPANY_REGISTER = 'template.manager.company.register';
    const TEMPLATE_MANAGER_COMPANY_FEEDBACK = 'template.manager.company.feedabck';
    const TEMPLATE_MANAGER_DEAL_FEEDBACK = 'template.manager.deal.feedabck';
    const TEMPLATE_FEED_NOTIFY_DEAL = 'template.feed.notify.deal';
    const TEMPLATE_FEED_NOTIFY_COMPANY = 'template.feed.notify.company';

    public function __construct()
    {
        $choices = [
            self::TEMPLATE_REGISTRATION => self::TEMPLATE_REGISTRATION,
            self::TEMPLATE_RESETING => self::TEMPLATE_RESETING,
            self::TEMPLATE_FEEDBACK_ANSWER_COMPANY => self::TEMPLATE_FEEDBACK_ANSWER_COMPANY,
            self::TEMPLATE_FEEDBACK_ANSWER_DEAL => self::TEMPLATE_FEEDBACK_ANSWER_DEAL,
            self::TEMPLATE_MANAGER_COMPANY_REGISTER => self::TEMPLATE_MANAGER_COMPANY_REGISTER,
            self::TEMPLATE_MANAGER_COMPANY_FEEDBACK => self::TEMPLATE_MANAGER_COMPANY_FEEDBACK,
            self::TEMPLATE_MANAGER_DEAL_FEEDBACK => self::TEMPLATE_MANAGER_DEAL_FEEDBACK,
            self::TEMPLATE_FEED_NOTIFY_COMPANY => self::TEMPLATE_FEED_NOTIFY_COMPANY,
            self::TEMPLATE_FEED_NOTIFY_DEAL => self::TEMPLATE_FEED_NOTIFY_DEAL,
        ];
        parent::__construct($choices);
    }
}
