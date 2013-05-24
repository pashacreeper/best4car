<?php
namespace Sto\ContentBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ObjectManager;
use Sto\CoreBundle\Entity\Dictionary\WeekDay;
use Doctrine\Common\Collections\ArrayCollection;

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
     * Transforms an object (issue) to a array (number).
     *
     * @param  Issue|null $issue
     * @return array
     */
    public function reverseTransform($weekDay)
    {
        if (null === $weekDay) {
            return "";
        }
        $res = '';
        $array = array();
        if (count($weekDay)>0) {
            $flag_start = false;
            $buf_position = -1;
            $buf_key = -1;
            foreach ($weekDay as $key => $day) {
                $array[] = $day->getId();
                if (!$flag_start) {
                    $res .= $day->getShortName();
                    $buf_position = $day->getPosition();
                    $flag_start = true;
                } else {
                    if ($day->getPosition()==$buf_position+1) {
                        $buf_key = $key;
                        $buf_position++;
                    } else {
                        $flag_start = false;
                        if ($buf_key >= 0) {
                            $res .= '-'.$weekDay[$buf_key]->getShortName();
                            $buf_key = -1;
                        }
                        $res .= ', '.$day->getShortName();
                    }
                }
            }
        }
        $data['string'] = $res;
        $data['array'] = $array;

        return $data;
    }

    /**
     * Transforms a string (number) to an object (issue).
     *
     * @param  string                        $number
     * @return Issue|null
     * @throws TransformationFailedException if object (issue) is not found.
     */
    public function transform($code)
    {
        if (!$code) {
            return null;
        }
        $ids = implode(',', $code['array']);
        $weekDay = $this->om
            ->getRepository('StoCoreBundle:Dictionary\WeekDay')
            ->createQueryBuilder('wd')
            ->where('wd.id IN ('.$ids.')')
            ->getQuery()
            ->getResult()
        ;
        if (null === $weekDay) {
            throw new TransformationFailedException(sprintf(
                'A weekDay with name "%s" does not exist!',
                $code['string']
            ));
        }

        $res = new ArrayCollection();
        foreach ($weekDay as $one) {
            $res->add($one);
        }

        return $res;
    }
}
