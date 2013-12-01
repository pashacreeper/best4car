<?php
namespace Sto\ContentBundle\Helper;

use Sto\CoreBundle\Entity\CompanyAutoService;
use Doctrine\Common\Persistence\ObjectManager;

class CompanyServiceHelper
{
    /**
     * @var ObjectManager
     */
    protected $em;

    public function __construct(ObjectManager $em)
    {
        $this->em = $em;
    }

    public function createCompanyServiceParent(&$companyServices, $companyService, $specialization)
    {
        $companyServiceParent = null;
        $service = $companyService->getService()->getParent();
        foreach ($companyServices as $seachCompanyService) {
            if ($service == $seachCompanyService->getService()) {
                $companyServiceParent = $seachCompanyService;
                break;
            }
        }
        if (!$companyServiceParent) {
            $companyServiceParent = new CompanyAutoService();
            $companyServiceParent->setService($service);
            $companyServiceParent->setSpecialization($specialization);
            $this->em->persist($companyServiceParent);
            $this->em->flush();
            $companyServices[] = $companyServiceParent;
        }
        if ($service->getParent()) {
            $this->createCompanyServiceParent($companyServices, $companyServiceParent, $specialization);
        }
    }

    public function setCompanyServiceParent($companyServices, $companyService)
    {
        foreach ($companyServices as $seachCompanyService) {
            if ($companyService->getService()->getParent() == $seachCompanyService->getService()) {
                $companyService->setParent($seachCompanyService);
                break;
            }
        }
    }
}
