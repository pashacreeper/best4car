<?php

namespace Sto\ContentBundle\Form\DataTransformer;

use Doctrine\Common\Persistence\ObjectManager;
use Sto\CoreBundle\Entity\CustomModification;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class IdToModificationTransformer implements DataTransformerInterface
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
     * @param CustomModification $value
     *
     * @return integer
     */
    public function transform($value)
    {
        if (null === $value) {
            return null;
        }

        return $value->getId();
    }

    /**
     * @param integer $id
     *
     * @return null|CustomModification
     * @throws \Symfony\Component\Form\Exception\TransformationFailedException
     */
    public function reverseTransform($id)
    {
        if (! $id) {
            return null;
        }

        $modification = $this->om
            ->getRepository('StoCoreBundle:CustomModification')
            ->findOneBy(['id' => $id]);

        if (null === $modification) {
            throw new TransformationFailedException(sprintf(
                'An modification with id "%s" does not exist!',
                $id
            ));
        }

        return $modification;
    }
}
