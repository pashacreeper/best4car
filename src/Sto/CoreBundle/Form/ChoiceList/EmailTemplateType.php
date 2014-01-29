<?php
namespace Sto\CoreBundle\Form\ChoiceList;

use Symfony\Component\Form\Extension\Core\ChoiceList\SimpleChoiceList;

class EmailTemplateType extends SimpleChoiceList
{
    const TEMPLATE_REGISTRATION = 'template.registration';
    const TEMPLATE_RESETING = 'template.reseting';
    const TEMPLATE_FEEDBACK_ANSWER = 'template.feedback_answer';
    const TEMPLATE_MANAGER_COMPANY_REGISTER = 'template.manager.company.register';
    const TEMPLATE_MANAGER_COMPANY_FEEDBACK = 'template.manager.company.feedabck';
    const TEMPLATE_MANAGER_DEAL_FEEDBACK = 'template.manager.deal.feedabck';

    public function __construct()
    {
        $choices = [
            self::TEMPLATE_REGISTRATION => self::TEMPLATE_REGISTRATION,
            self::TEMPLATE_RESETING => self::TEMPLATE_RESETING,
            self::TEMPLATE_FEEDBACK_ANSWER => self::TEMPLATE_FEEDBACK_ANSWER,
            self::TEMPLATE_MANAGER_COMPANY_REGISTER => self::TEMPLATE_MANAGER_COMPANY_REGISTER,
            self::TEMPLATE_MANAGER_COMPANY_FEEDBACK => self::TEMPLATE_MANAGER_COMPANY_FEEDBACK,
            self::TEMPLATE_MANAGER_DEAL_FEEDBACK => self::TEMPLATE_MANAGER_DEAL_FEEDBACK,
        ];
        parent::__construct($choices);
    }
}
