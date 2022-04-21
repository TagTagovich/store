<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Source;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use App\Repository\BaseRepository;
use App\Repository\SourceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/product")
 */
class ProductController extends AbstractController
{
    /**
     * @Route("/", name="app_product_index", methods={"GET"})
     */
    public function index(ProductRepository $productRepository, BaseRepository $baseRepository): Response
    {
        

        return $this->render('product/index.html.twig', [
            'products' => $productRepository->findAll(),
            'baseList' => $baseRepository->findAll()
        ]);
    }

    /**
     * @Route("/new/{baseId}", name="app_product_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ProductRepository $productRepository, BaseRepository $baseRepository, int $baseId): Response
    {
        $product = new Product();
        $base = $baseRepository->find($baseId);
        foreach($base->getPlaces() as $place){
        $source = new Source();
        $source->setPlace($place);
        //$source->setProduct($product);
        $product->addSource($source);
        }
        
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            //$productRepository->add($product);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();
            return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
        }
        
        return $this->renderForm('product/new.html.twig', [
            'product' => $product,
            'form' => $form,
            
        ]);
    }

    /**
     * @Route("/{id}", name="app_product_show", methods={"GET"})
     */
    public function show(Product $product, SourceRepository $sourceRepository): Response
    {
        return $this->render('product/show.html.twig', [
            'product' => $product,
            'sources' => $sourceRepository->findBy(['product' => $product])
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_product_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Product $product, ProductRepository $productRepository): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $productRepository->add($product);
            return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('product/edit.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/delete/{id}", name="app_product_delete")
     */
    public function delete(Request $request, Product $product): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($product);
        $entityManager->flush();

        return $this->redirectToRoute('app_product_index');
    }
}