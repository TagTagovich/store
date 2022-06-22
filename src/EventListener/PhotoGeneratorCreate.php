<?php

namespace App\EventListener;

use App\Entity\Photo;
use App\Entity\Source;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class PhotoGeneratorCreate
{

    private $photoFileDirectory;
    
    public function __construct(string $photoFileDirectory)
    {

        $this->photoFileDirectory = $photoFileDirectory;
    }

    public function postPersist(LifecycleEventArgs $args): void
    {
            $entity = $args->getObject();

            if ($entity instanceof Source) {
                $entityManager = $args->getObjectManager();    
                $photo = new Photo();
                $photo->setFile('photo_' . $entity->getId() . '_' . $entity->getPlace()->getId() . '.png');
                $entityManager->persist($photo);
                $entityManager->flush();
            }
    }
}