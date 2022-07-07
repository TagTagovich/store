<?php

namespace App\EventListener;

use App\Entity\Source;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Filesystem\Filesystem;
use Imagick;

class SourceImageResize
{

    private $sourceFileDirectory;

    
    public function __construct(string $sourceFileDirectory)
    {
        $this->sourceFileDirectory = $sourceFileDirectory;
 
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
            $entity = $args->getObject();
            if ($entity instanceof Source) {
                $entityManager = $args->getObjectManager();    
                //$source = $entityManager->getRepository(Source::class)->find($entity);
                  
                $thumb = new \Imagick();
                $thumb->readImage($entity->getFile());
                $width = $source->getWidth();
                $height = $source->getHeight();

                $thumb->resizeImage($thumb->getImageWidth(), 1000, Imagick::FILTER_LANCZOS, 1, true);
                $thumb->writeImage($this->sourceFileDirectory . $source->getImageFilename());
                $thumb->clear();
                $thumb->destroy();     
                //$entityManager->flush();
                

            }
    
    }
}