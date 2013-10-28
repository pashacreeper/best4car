<?php
namespace Sto\ContentBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ObjectManager;
use Sto\UserBundle\Entity\User;

class CompanyManagerTransformer implements DataTransformerInterface
{
    /**
     * @var ObjectManager
     */
    private $om;

    /**
     * @param ObjectManager $om
     */
    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }

    /**
     * Transforms an object (issue) to a array (number).
     *
     * @param mixed $username
     *
     * @internal param null|\Sto\ContentBundle\Form\DataTransformer\Issue $issue
     * @return array
     */
    public function reverseTransform($username)
    {
        if ($username == '')
            return null;

        $user = $this->om
            ->getRepository('StoUserBundle:User')
            ->findOneBy(['username' => $username])
        ;

        return $user;

    }

    /**
     * Transforms a string (number) to an object (issue).
     *
     * @param mixed $user
     *
     * @internal param string $number
     *
     * @return Issue|null
     */
    public function transform($user)
    {
        if ($user) {
            return $user->getUsername();
        }

        return '';
    }
}
