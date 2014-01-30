<?php
namespace Sto\CoreBundle\Service;

use Sto\CoreBundle\Entity\Deal;
use Sto\CoreBundle\Entity\Company;
use Sto\UserBundle\Entity\User;

class EmailTemplateTransformer
{
    /**
     * Transforming template string to message
     *
     * @param string $template Template string
     * @param  array
     *
     * @internal param \Sto\UserBundle\Entity\User $user
     * @internal param \Sto\CoreBundle\Entity\Company $company
     * @return string transformed template string
     */
    public function transform($template)
    {
        if ($this->checkData($data = func_get_args()[1])) {
            if (isset($data['user']) && $user = $data['user']) {
                $template = $this->transformUser($template, $user);
            }

            if (isset($data['company']) && $company = $data['company']) {
                $template = $this->transformCompany($template, $company);
            }

            if (isset($data['deal']) && $deal = $data['deal']) {
                $template = $this->transformDeal($template, $deal);
            }

            if (isset($data['link']) && $link = $data['link']) {
                $template = $this->transformLink($template, $link);
            }
        }

        return $template;
    }

    /**
     * Check if provided data is valid
     * @param array $data
     *
     * @return bool
     * @throws \Exception
     */
    private function checkData(array $data)
    {
        $availableOptions = [
            'user',
            'company',
            'link',
            'deal'
        ];

        $dataKeys = array_keys($data);

        foreach ($dataKeys as $key) {
            if (!in_array($key, $availableOptions)) {
                throw new \Exception("Don't support this type of option");
            }
        }

        if (isset($data['user']) && !($data['user'] instanceof User)) {
            throw new \Exception('Wrong data type provided');
        }

        if (isset($data['company']) && !($data['company'] instanceof Company)) {
            throw new \Exception('Wrong data type provided');
        }

        if (isset($data['deal']) && !($data['deal'] instanceof Deal)) {
            throw new \Exception('Wrong data type provided');
        }

        return true;
    }

    /**
     * @param      $template
     * @param User $user
     *
     * @return mixed
     */
    private function transformUser($template, User $user)
    {
        return str_replace(
            [
                '%user%',
                '%email%',
            ],
            [
                $user->getUsername(),
                $user->getEmail(),
            ],
            $template
        );
    }

    /**
     * @param         $template
     * @param Company $company
     *
     * @return mixed
     */
    private function transformCompany($template, Company $company)
    {
        return str_replace(
            [
                '%company%'
            ],
            [
                $company->getTitle()
            ],
            $template
        );
    }

    /**
     * @param      $template
     * @param Deal $deal
     *
     * @return mixed
     */
    private function transformDeal($template, Deal $deal)
    {
        return str_replace(
            [
                '%deal%'
            ],
            [
                $deal->getTitle()
            ],
            $template
        );
    }

    /**
     * @param $template
     * @param $link
     *
     * @return mixed
     */
    private function transformLink($template, $link)
    {
        return str_replace('%link%', $link, $template);
    }
}
