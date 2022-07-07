<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Twig\Environment;
use App\Entity\Place;
use App\Entity\Product;
use App\Entity\Photo;

class PreResizeService
{    
    private function writeThumbnail($document, $filter) {
        $path = $document->getWebPath();                                // domain relative path to full sized image
        $tpath = $document->getRootDir().$document->getThumbPath();     // absolute path of saved thumbnail

        $container = $this->container;                                  // the DI container
        $dataManager = $container->get('liip_imagine.data.manager');    // the data manager service
        $filterManager = $container->get('liip_imagine.filter.manager');// the filter manager service

        $image = $dataManager->find($filter, $path);                    // find the image and determine its type
        $response = $filterManager->get($this->getRequest(), $filter, $image, $path); // run the filter 
        $thumb = $response->getContent();                               // get the image from the response

        $f = fopen($tpath, 'w');                                        // create thumbnail file
        fwrite($f, $thumb);                                             // write the thumbnail
        fclose($f);                                                     // close the file
    }
}