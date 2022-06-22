<?php

namespace App\EventListener;

use App\Entity\Place;
use App\Entity\Base;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class BaseChangedNotifier
{

    public function postUpdate(LifecycleEventArgs $args): void
    {
            $entity = $args->getObject();
            
            //$place = new Place();
            if ($entity instanceof Base) {
                $entityManager = $args->getObjectManager();    
                $places = $entityManager->getRepository(Place::class)->findByBase($entity);
                foreach ($places as $place) {
                    if(!empty($place->getWidth() and $place->getHeight() and $place->getStartX() and $place->getStartY())) {
                        $entity->setStatus("ready");
                        $entityManager->flush();
                        break; 
                    }
                }
            }
            
    }
}
