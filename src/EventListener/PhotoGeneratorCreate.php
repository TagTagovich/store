<?php

namespace App\EventListener;

use App\Entity\Photo;
use App\Entity\Source;
use App\Entity\Place;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Filesystem\Filesystem;
use Imagick;

class PhotoGeneratorCreate
{

    private $placeFileDirectory;
    private $sourceFileDirectory;
    private $tmpFileDirectory;
    private $photoFileDirectory;

    
    public function __construct(string $placeFileDirectory, $sourceFileDirectory, $tmpFileDirectory, $photoFileDirectory)
    {
        $this->placeFileDirectory = $placeFileDirectory;
        $this->sourceFileDirectory = $sourceFileDirectory;
        $this->tmpFileDirectory = $tmpFileDirectory;
        $this->photoFileDirectory = $photoFileDirectory;
    }

    public function postPersist(LifecycleEventArgs $args): void
    {
            $entity = $args->getObject();

            if ($entity instanceof Source) {
                $entityManager = $args->getObjectManager();    
                
                $source = $entityManager->getRepository(Source::class)->find($entity);
                    
                $thumb = new \Imagick();
                $thumb->readImage($this->sourceFileDirectory . $source->getFileName());
                $width = $source->getPlace()->getWidth();
                $height = $source->getPlace()->getHeight();
                $startX = $source->getPlace()->getStartX();
                $startY = $source->getPlace()->getStartY();
                $thumb->resizeImage($width, $height, Imagick::FILTER_LANCZOS, 1);
                $thumb->writeImage($this->tmpFileDirectory . 'tmp_source_' . $source->getId() . '.png');
                $thumb->clear();
                $thumb->destroy();     
                    
                $src1 = new \Imagick($this->placeFileDirectory . $source->getPlace()->getImageFileName());
                    
                // предварительно сжатое изображение
                $src2 = new \Imagick($this->tmpFileDirectory . 'tmp_source_' . $source->getId() . '.png');

                $src1->compositeImage($src2, imagick::COMPOSITE_MULTIPLY, $startX, $startY);
                $src1->writeImage($this->photoFileDirectory . 'photo_' . $source->getId() . '_' . $source->getPlace()->getId() . '.png');
                $fs = new Filesystem();
                $fs->remove([$this->tmpFileDirectory . 'tmp_source_' . $source->getId() . '.png']);

                $entityManager->flush();

                $photo = new Photo();
                $photo->setFile('photo_' . $entity->getId() . '_' . $entity->getPlace()->getId() . '.png');

                $entityManager->persist($photo);
                $entityManager->flush();
                $place = $entityManager->getRepository(Place::class)->find($entity->getPlace());
                $place->setPhoto($photo);               
                $entityManager->flush();
            }
    }
}