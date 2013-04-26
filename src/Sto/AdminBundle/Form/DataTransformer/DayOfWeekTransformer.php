<?php
namespace Sto\AdminBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ObjectManager;
use Sto\CoreBundle\Entity\Dictionary\WeekDay;

class DayOfWeekTransformer implements DataTransformerInterface
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
     * Transforms an object (issue) to a string (number).
     *
     * @param  Issue|null $issue
     * @return string
     */
    public function reverseTransform($weekDay)
    {
        if (null === $weekDay) {
            return "";
        }

        return $weekDay->getShortName();
    }

    /**
     * Transforms a string (number) to an object (issue).
     *
     * @param  string $number
     * @return Issue|null
     * @throws TransformationFailedException if object (issue) is not found.
     */
    public function transform($code)
    {
        if (!$code) {
            return null;
        }

        $weekDay = $this->om
            ->getRepository('StoCoreBundle:Dictionary\WeekDay')
            ->findOneBy(['shortName'=>$code])
        ;

        if (null === $weekDay) {
            throw new TransformationFailedException(sprintf(
                'A weekDay with name "%s" does not exist!',
                $code
            ));
        }

        return $weekDay;
    }
}

