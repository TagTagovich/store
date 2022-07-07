<?php

namespace App\Validator;

use App\Repository\PlaceRepository;
use App\Repository\SourceRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Imagick;

class SourceFileValidator extends ConstraintValidator
{
    private $placeRepository;
    private $sourceRepository;
    private $container;
    
    public function __construct(PlaceRepository $placeRepository, SourceRepository $sourceRepository, ContainerInterface $container)
    {
        $this->placeRepository = $placeRepository;
        $this->sourceRepository = $sourceRepository;
        $this->container = $container;

    }
    
    public function validate($source, Constraint $constraint)
    {
        $src1 = new \Imagick();
        $src1->readImage($source->getFile());
        $sourceWidth = $src1->getImageWidth();
        $sourceHeight = $src1->getImageHeight();
        $sourceResolution = $src1->getImageResolution();
        $sourceXDPI = (int) $sourceResolution['x'];
        $sourceYDPI = (int) $sourceResolution['y'];
        $src2 = new \Imagick();
        $src2->readImage($this->container->getParameter('app.place_file_directory') . $source->getPlace()->getImageFileName());
        $placeWidth = $src2->getImageWidth();
        $placeHeight = $src2->getImageHeight();
        $placeResolution = $src2->getImageResolution();
        $placeXDPI = (int) $placeResolution['x'];
        $placeYDPI = (int) $placeResolution['y'];
        $src1->clear();
        $src2->clear();
        $src1->destroy();
        $src2->destroy();
        if ($sourceWidth != $placeWidth or $sourceHeight != $placeHeight or $sourceXDPI != $placeXDPI or $sourceYDPI != $placeYDPI) {
            $this->context->buildViolation($constraint->message)
                ->atPath('file')
                ->addViolation();
        }
    }
}

