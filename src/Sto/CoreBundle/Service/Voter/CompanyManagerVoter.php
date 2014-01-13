<?php
namespace Sto\CoreBundle\Service\Voter;

use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\Common\Persistence\ObjectManager;

class CompanyManagerVoter implements VoterInterface 
{
    /**
     * @var EntityManager
     */
    protected $em;

    public function setEntityManager(ObjectManager $em)
    {
        $this->em = $em;
    }

    public function supportsAttribute($attribute)
    {
        return in_array($attribute, ["EDIT", "DELETE", "SHOW"]);
    }

    public function supportsClass($class)
    {
        $classes = ['Sto\CoreBundle\Entity\Company'];

        return in_array($class, $classes);
    }

    public function vote(TokenInterface $token, $object, array $attributes)
    {
        if (!$this->supportsClass(get_class($object)) || !$user = $token->getUser()) {
            return VoterInterface::ACCESS_ABSTAIN;
        }

        $companyManagerRepository = $this->em->getRepository('StoCoreBundle:CompanyManager');

        if ($companyManagerRepository->isUserManager($user->getId(), $object->getId()) 
            || $user->hasRole('ROLE_ADMIN')) 
        {
            return VoterInterface::ACCESS_GRANTED;
        }

        return VoterInterface::ACCESS_DENIED;
    }
}