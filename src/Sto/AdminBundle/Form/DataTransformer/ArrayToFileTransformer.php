<?php
namespace Sto\AdminBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ObjectManager;
use Sto\CoreBundle\Entity\Deal;

class ArrayToFileTransformer implements DataTransformerInterface
{
    /**
     * @var ObjectManager
     */
    private $om;

    /**
     * @param ObjectManager $om
     */
    public function __construct()
    {
    }


    public function transform($deal)
    {
        //print "TRANSOFRM"; exit;
        if (null === $deal) {
            return array();
        }

        return $issue->getImage();
    }


    public function reverseTransform($array)
    {
        /*print "reverseTransfor:";
        print '<pre>';
        print_r($array);
        print '</pre>'; exit;*/
        if (!$array) {
            return null;
        }

        $image = $array[0];
        /*print '<pre>';
        print_r($image);
        print '</pre>'; exit;*/

        /*
        $issue = $this->om
            ->getRepository('AcmeTaskBundle:Issue')
            ->findOneBy(array('number' => $number))
        ;

        if (null === $issue) {
            throw new TransformationFailedException(sprintf(
                'An issue with number "%s" does not exist!',
                $number
            ));
        }
        */

        return $image;
    }
}
