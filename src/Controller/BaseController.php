<?php

namespace App\Controller;

use App\Entity\Base;
use App\Entity\Place;
use App\Form\BaseType;
use App\Form\PlaceType;
use App\Repository\BaseRepository;
use App\Repository\PlaceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @Route("/base")
 */
class BaseController extends AbstractController
{

    /**
     * @Route("/", name="app_base_index", methods={"GET"})
     */
    public function index(ManagerRegistry $doctrine, Request $request, BaseRepository $baseRepository): Response
    {
        $qb = $baseRepository->createQueryBuilder('b');

        if ($request->query->get('q')) {
            $qb->andWhere('b.name like :q')->setParameter('q', '%' . $request->query->get('q') . '%');
        }
        
        $mass = $qb->getQuery()->getResult();
        foreach ($qb->getQuery()->getResult() as $value) {
            
        }
        $places = $doctrine->getRepository(Place::class)->findByBase(40);

        
        
        //$places = $base->getPlaces();

        return $this->render('base/index.html.twig', [
            'bases' => $qb->getQuery()->getResult(),
            'places' => $places
        ]);
    }

    /**
     * @Route("/new", name="app_base_new", methods={"GET", "POST"})
     */
    public function new(Request $request, BaseRepository $baseRepository, PlaceRepository $placeRepository): Response
    {
        $base = new Base();
        
        //$eventDispatcher = new EventDispatcher();
        $form = $this->createForm(BaseType::class, $base);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $baseRepository->add($base);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            $place = $placeRepository->findBy(['base' => $base]);

            return $this->redirectToRoute('app_base_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('base/new.html.twig', [
            'base' => $base,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_base_show", methods={"GET"})
     */
    public function show(Base $base, PlaceRepository $placeRepository): Response
    {
        return $this->render('base/show.html.twig', [
            'base' => $base,
            'places' => $placeRepository->findBy(['base' => $base])
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_base_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Base $base, BaseRepository $baseRepository): Response
    {
        
        $form = $this->createForm(BaseType::class, $base);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $baseRepository->add($base);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            
            return $this->redirectToRoute('app_base_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('base/edit.html.twig', [
            'base' => $base,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/delete/{id}", name="app_base_delete")
     */
    public function delete(Request $request, Base $base): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($base);
        $entityManager->flush();

        return $this->redirectToRoute('app_base_index');
    }

    /**
     * @Route("/place/set/{place}", methods={"POST", "GET"}, name="place_setter")
     */
    public function __invoke(Place $place): JsonResponce
    {
        $event = new EventStatus($place);
        $this->eventDispatcher->dispatch($event, EventStatus::NAME);

        $json = $this->serializer->serialize($place, 'json', ['groups' => ['place:get']]);
        
        return new JsonResponse($json, JsonResponse::HTTP_OK, [], true);
    }
}
