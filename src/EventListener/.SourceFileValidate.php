<?php

namespace App\EventListener;

use App\Entity\Source;
use App\Entity\Place;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Filesystem\Filesystem;
use Imagick;

class SourceFileValidate
{

    private $placeFileDirectory;
    private $sourceFileDirectory;

    public function __construct(string $placeFileDirectory, $sourceFileDirectory)
    {
        $this->placeFileDirectory = $placeFileDirectory;
        $this->sourceFileDirectory = $sourceFileDirectory;

    }

    public function onFlush(OnFlushEventArgs $args)
    {
        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();

        foreach ($uow->getScheduledEntityUpdates() as $entity) {
            if ($entity instanceof Source) {
                $fs = new Filesystem();        
                //$source = $entityManager->getRepository(Source::class)->find($entity);
                
                $src1 = new \Imagick();
                $src1->readImage($entity->getPlace()->getImage());
                $imgDpi1 = $src1->getImageResolution();
                $src2 = new \Imagick();
                $src2->readImage($entity->getFile());
                $imgDpi2 = $src2->getImageResolution();
                if ($imgDpi1['x'] != $imgDpi2['x']) {
                    $em->remove($entity);
                    $uow->computeChangeSet();
                }

                $src1->clear();
                $src2->clear();
                $src1->destroy();
                $src2->destroy();
            }
        }
    }
} 