<?php

namespace App\EventListener;

use App\Entity\Place;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Filesystem\Filesystem;
use Imagick;

class PlaceImageResize
{

    private $placeFileDirectory;

    
    public function __construct(string $placeFileDirectory)
    {
        $this->placeFileDirectory = $placeFileDirectory;
 
    }

    public function postPersist(LifecycleEventArgs $args): void
    {
            $entity = $args->getObject();
            if ($entity instanceof Place) {
                $entityManager = $args->getObjectManager();    
                $place = $entityManager->getRepository(Place::class)->find($entity);
                  
                $thumb = new \Imagick();
                $thumb->readImage($this->placeFileDirectory . $place->getImageFilename());
                $width = $place->getWidth();
                $height = $place->getHeight();

                $thumb->resizeImage($thumb->getImageWidth(), 1000, Imagick::FILTER_LANCZOS, 1, true);
                $thumb->writeImage($this->placeFileDirectory . $place->getImageFilename());
                $thumb->clear();
                $thumb->destroy();     
                //$entityManager->flush();
                

            }
    
    }
}