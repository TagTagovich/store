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
        return $this->render('place/index.html.twig', [
            'places' => $placeRepository->findBy(['base' => $base]),
            'base' => $base->getId()
            
            
        ]);
    }

    /**
     * @Route("/new", name="app_place_new", methods={"GET", "POST"})
     */
    public function new(Request $request, PlaceRepository $placeRepository): Response
    {
        $place = new Place();
        $form = $this->createForm(PlaceType::class, $place);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $placeRepository->add($place);
            return $this->redirectToRoute('app_place_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('place/new.html.twig', [
            'place' => $place,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{base}/{id}/edit", name="app_place_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, int $base, Place $place, PlaceRepository $placeRepository): Response
    {
        $form = $this->createForm(PlaceType::class, $place);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $placeRepository->add($place);
            return $this->redirectToRoute('app_place_index', ['id' => $base], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('place/edit.html.twig', [
            'base' => $base,
            'place' => $place,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_place_delete", methods={"POST"})
     */
    public function delete(Request $request, Place $place, PlaceRepository $placeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$place->getId(), $request->request->get('_token'))) {
            $placeRepository->remove($place);
        }

        return $this->redirectToRoute('app_place_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/{id}/{startX}/{startY}/{width}/{height}/overlase_save", name="app_overlace_save", methods={"GET", "POST"})
     */
    public function overlace_save(Request $request, int $id, ManagerRegistry $doctrine, PlaceRepository $placeRepository): Response
    {
        $entityManager = $doctrine->getManager();

        $startX = $request->query->get("startX");
        $startY = $request->query->get("startY");
        $width = $request->query->get("width");
        $height = $request->query->get("height");
        
        $place = $entityManager->getRepository(Place::class)->find($id);
        $place->setStartX($startX);
        $place->setStartY($startY);
        $place->setWidth($width);
        $place->setHeight($height);

        $entityManager->persist($place);

        $entityManager->flush();

        return $this->json(['left' => $startX, 'top' => $startY, 'width' => $width, 'height' => $height]);
        
    }

    /**
     * @Route("/{id}/overlace_edit", name="app_overlace_edit", methods={"GET", "POST"})
     */
    public function overlace_edit(Request $request, ManagerRegistry $doctrine, int $id, Place $place, PlaceRepository $placeRepository): Response
    {
        $place = $doctrine->getRepository(Place::class)->find($id);
        //$image = $doctrine->getRepository(Place::class)->find($id);
        $imageFilename = $place->getImageFilename();
        $base = $place->getBase();
        //$uri = $this->container->getParameter('place_image') . $imageFilename;

        return $this->renderForm('place/overlace_edit.html.twig', [
            'base' => $base,
            //'uri' => $uri,
            'place' => $place
        ]);
    }
}
