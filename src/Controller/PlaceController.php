<?php

namespace App\Controller;

use App\Entity\Place;
use App\Form\PlaceType;
use App\Entity\Base;
use App\Repository\PlaceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Liip\ImagineBundle\Service\FilterService;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\Stream;

/**
 * @Route("/place")
 */
class PlaceController extends AbstractController
{
    /**
     * @Route("/{id}", name="app_place_index", methods={"GET"})
     */
    public function index(Base $base, PlaceRepository $placeRepository): Response
    {
        $place = $placeRepository->findBy(['base' => $base]);
        
        return $this->render('place/index.html.twig', [
            'places' => $placeRepository->findBy(['base' => $base]),
            'base' => $base->getId()
            
            
        ]);
    }

    /**
     * @Route("/{id}/rectangle_get", name="app_rectangle_get", methods={"GET", "POST"})
     */
    public function rectangle_get(Request $request, int $id, ManagerRegistry $doctrine, PlaceRepository $placeRepository): Response
    {
        $entityManager = $doctrine->getManager();
        $place = $entityManager->getRepository(Place::class)->find($id);
            $startX = $place->getStartX();
            $startY = $place->getStartY();
            $width = $place->getWidth();
            $height = $place->getHeight();
            $isCoords = !empty($startX and $startY and $width and $height);
            
            return $this->json(['is' => $isCoords, 'left' => $startX, 'top' => $startY, 'width' => $width, 'height' => $height]);
    }

    /**
     * @Route("/{id}/{startX}/{startY}/{width}/{height}/overlase_save", name="app_overlace_save", methods={"GET", "POST"})
     */
    public function overlace_save(Request $request, int $id, ManagerRegistry $doctrine, PlaceRepository $placeRepository): Response
    {
        $entityManager = $doctrine->getManager();
        $place = $entityManager->getRepository(Place::class)->find($id);
            
        $startX = $request->query->get("startX");
        $startY = $request->query->get("startY");
        $width = $request->query->get("width");
        $height = $request->query->get("height");
        $place->setStartX($startX);
        $place->setStartY($startY);
        $place->setWidth($width);
        $place->setHeight($height);
        $entityManager->persist($place);    
        $entityManager->flush();
            
        return $this->json(['response' => 'coordinates updated successfully']);
                
    }  
    
    /**
     * @Route("/{id}/overlace_edit", name="app_overlace_edit", methods={"GET", "POST"})
     */
    public function overlace_edit(Request $request, ManagerRegistry $doctrine, int $id, Place $place, PlaceRepository $placeRepository, FilterService $imagine): Response
    {

        $place = new Place();
        $base = $place->getBase();

        return $this->renderForm('place/overlace_edit.html.twig', [
            'base' => $base,
            //'uri' => $uri,
            'place' => $place
        ]);
    }
}        
       
     

 
        


        




