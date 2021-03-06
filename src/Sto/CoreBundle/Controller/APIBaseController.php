<?php

namespace Sto\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\FOSRestController;
use Sto\CoreBundle\Entity\Dictionary\Base;

/**
 * Base controller.
 */
class APIBaseController extends FOSRestController
{

    public function getAction($id)
    {
        $serializer = $this->container->get('jms_serializer');

        $em = $this->getDoctrine()->getManager();
        $data = $em->createQueryBuilder()
            ->select('b')
            ->from('StoCoreBundle:Dictionary\Base', 'b')
            ->where('b.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getArrayResult()
        ;

        if ($data === NULL)
            return new Response($serializer->serialize(array("message" => "Not found Item", "type" => "error", "code" => 404, ), 'json'), 404);
        else
            return new Response($serializer->serialize($data, 'json'));
    }

    public function deleteAction($id)
    {
        $serializer = $this->container->get('jms_serializer');

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('StoCoreBundle:Dictionary\Base')->findOneById($id );

        if (!$entity) {
            return new Response($serializer->serialize(array("message" => "Not found Item", "type" => "error", "code" => 404, ), 'json'), 404);
        }
        $em->remove($entity);
        $em->flush();

        return new Response();
    }
}
