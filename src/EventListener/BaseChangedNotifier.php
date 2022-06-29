<?php

namespace App\EventListener;

use App\Entity\Place;
use App\Entity\Base;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Filesystem\Filesystem;

class BaseChangedNotifier
{
    
    public function postUpdate(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();
        if ($entity instanceof Place) {
            $entityManager = $args->getObjectManager();    
            $places = $entityManager->getRepository(Place::class)->findByBase($entity->getBase());
            $count = count($places);
            $i = 0;
            foreach ($places as $place) {
                if(!empty($place->getWidth() and $place->getHeight() and $place->getStartX() and $place->getStartY())) {
                    $i++;
                    //break;
                }    
            }    
            if ($count == $i) {
                $base = $entityManager->getRepository(Base::class)->find($entity->getBase());
                $base->setStatus("ready");
                $entityManager->flush();                
            }
        }
    }
}  
                
            
           
