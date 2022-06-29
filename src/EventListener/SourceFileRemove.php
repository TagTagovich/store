<?php

namespace App\EventListener;

use App\Entity\Source;
use App\Entity\Place;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Symfony\Component\Filesystem\Filesystem;

class SourceFileRemove
{

    private $sourceFileDirectory;

    public function __construct(string $sourceFileDirectory)
    {
        $this->sourceFileDirectory = $sourceFileDirectory;
    }

    public function onFlush(OnFlushEventArgs $args)
    {
        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();

        foreach ($uow->getScheduledEntityUpdates() as $entity) {
            if ($entity instanceof Source) {
                $fs = new Filesystem();        
                $fs->remove([$this->sourceFileDirectory . $entity->getFileName()]);
            }
        }
    }
} 
       
