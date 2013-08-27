<?php

namespace Sto\CoreBundle\Services\Listeners;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Sto\CoreBundle\Entity\Country;

class CountryListener
{
    public function preRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $connection = $args->getEntityManager()->getConnection();

        if ($entity instanceof Country) {
            $city_id = $entity->getId();

            $queries = [
                "UPDATE companies SET city_id = NULL WHERE city_id = {$city_id}",
                "UPDATE users SET city_id = NULL WHERE city_id = {$city_id}",
                "DELETE FROM Country WHERE parent_id = {$city_id}"
            ];

            $connection->prepare(implode(';', $queries))->execute();
        }

    }
}