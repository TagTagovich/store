<?php

namespace App\EventListener;

use App\Entity\Photo;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Filesystem\Filesystem;

class PhotoFileRemove
{
    private $photoFileDirectory;

    public function __construct(string $photoFileDirectory)
    {
        $this->photoFileDirectory = $photoFileDirectory;
    }

    public function preRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if ($entity instanceof Photo) {
                $em = $args->getObjectManager();
                $fs = new Filesystem();        
                $fs->remove([$this->photoFileDirectory . $entity->getFile()]);
        }
        
    }
} 