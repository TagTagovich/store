<?php

namespace App\EventListener;

use App\Entity\Product;
use App\Entity\Source;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class ProductSkuGeneratorUpdate
{

    public function postUpdate(LifecycleEventArgs $args): void
    {
            $entity = $args->getObject();

            if ($entity instanceof Product) {
                $entityManager = $args->getObjectManager();    
                $source = $entityManager->getRepository(Source::class)->findBy(['product' => $entity]);
                $baseId = $source[0]->getPlace()->getBase()->getId();
                $entity->setSku($baseId . '-' . $entity->getId());
                $entityManager->flush();
            
            }
    }
}