<?php

namespace App\Controller;

use App\Entity\Base;
use App\Form\BaseType;
use App\Entity\Place;
use App\Form\PlaceType;
use App\Repository\BaseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/base")
 */
class BaseController extends AbstractController
{
    /**
     * @Route("/", name="app_base_index", methods={"GET"})
     */
    public function index(BaseRepository $baseRepository): Response
    {
        return $this->render('base/index.html.twig', [
            'bases' => $baseRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_base_new", methods={"GET", "POST"})
     */
    public function new(Request $request, BaseRepository $baseRepository): Response
    {
        $base = new Base();
        $form = $this->createForm(BaseType::class, $base);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $baseRepository->add($base);
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
    public function show(Base $base): Response
    {
        return $this->render('base/show.html.twig', [
            'base' => $base,
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
            return $this->redirectToRoute('app_base_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('base/edit.html.twig', [
            'base' => $base,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_base_delete", methods={"POST"})
     */
    public function delete(Request $request, Base $base): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($base);
        $entityManager->flush();

        return $this->redirectToRoute('app_base_index');
    }
}
