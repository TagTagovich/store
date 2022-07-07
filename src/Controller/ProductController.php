<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Place;
use App\Entity\Source;
use App\Entity\Photo;
use App\Entity\Order;
use App\Form\ProductType;
use App\Service\ImportYML;
use App\Repository\ProductRepository;
use App\Repository\PhotoRepository;
use App\Repository\OrderRepository;
use App\Repository\BaseRepository;
use App\Repository\SourceRepository;
use App\Repository\PlaceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
//use Vich\UploaderBundle\Handler\UploadHandler;
use Imagick;

/**
 * @Route("/product")
 */
class ProductController extends AbstractController
{
    private $serviceImportYML;
    private $productPhoto;

    public function __construct(ImportYML $serviceImportYML)
    {
        $this->serviceImportYML = $serviceImportYML;
    }
    

    /**
     * @Route("/", name="app_product_index", methods={"GET"})
     */
    public function index(Request $request, ProductRepository $productRepository, BaseRepository $baseRepository, PlaceRepository $placeRepository, SourceRepository $sourceRepository, PhotoRepository $photoRepository): Response
    {
        $finder = new Finder();
        $fs = new Filesystem();
        $fs->remove($finder->files()->in($this->getParameter('app.tmp_file_directory')));
        $qbProduct = $productRepository->createQueryBuilder('p');
        $qbBase = $placeRepository->createQueryBuilder('p');
        $qbPhoto = $photoRepository->createQueryBuilder('p');

        if($baseIdOne = $request->query->get('base')){
            $productQuery = $productRepository->createQueryBuilder('p');
              $productQuery  
                ->join('p.sources', 's')
                ->join('s.place', 'pl')
                ->join('pl.base', 'b')
                ->where('b.id = :baseId')->setParameter('baseId', $baseIdOne);
               
        $productByBase = $productQuery->getQuery()->getResult();    

        } else { $productByBase = null; }
       
        if($request->query->get('q')){
            $qbProduct->andWhere('p.name like :q')->setParameter('q', '%' . $request->query->get('q') . '%');
        }


        return $this->render('product/index.html.twig', [
            'productByBases' => $productByBase,
            'products' => $qbProduct->getQuery()->getResult(),
            'baseList' => $baseRepository->findByStatus("ready"),
            'photos' => $photoRepository->findAll(),
            'sources' => $sourceRepository->findAll(),
        ]);
    }

    /**
     * @Route("/photo/to/{getting}", name="app_product_photo", methods={"GET", "POST"})
     */
    public function list(Request $request): Response
    {
        $this->productPhoto = $request->query->get("getting");
        dump($this->productPhoto);
        return $this->json(['response' => $this->productPhoto]);    
    }

    /**
     * @Route("/new/{baseId}", name="app_product_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ProductRepository $productRepository, BaseRepository $baseRepository, PlaceRepository $placeRepository, int $baseId): Response
    {
        
        $qbBase = $placeRepository->createQueryBuilder('p');
        if($baseId){
            $qbBase
                ->select('p.name')
                ->where('p.base = :id')
                ->setParameter('id', $baseId);
        }
        $placesNames = [];
        foreach($qbBase->getQuery()->getResult() as $value){
            $placesNames[] = $value['name'];
        }
        $product = new Product();
        $base = $baseRepository->find($baseId);
        $product->setPrice($base->getPrice());
        foreach($base->getPlaces() as $place){
            $source = new Source();
            $source->setPlace($place);
            //$source->setProduct($product);
            $product->addSource($source);
        }
        
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $product->setPrice($base->getPrice());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();
            $product->setSku($baseId . '-' . $product->getId());
            $entityManager->flush();
            $fs = new Filesystem();
            $pathToFileYML = $this->getParameter('app.import_yml_directory') . $this->getParameter('app.import_product_file_name');
            $isFile = $fs->readlink($pathToFileYML, true);
            if ($isFile) {
                $this->serviceImportYML->addOffer($product);
            } else {
                $this->serviceImportYML->createTemplate();
            }
            return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
        }
        
        return $this->renderForm('product/new.html.twig', [
            'placeNames' => $placesNames,
            'product' => $product,
            'form' => $form,
            
        ]);
    }

    /**
     * @Route("/{id}", name="app_product_show", methods={"GET"})
     */
    public function show(Product $product, SourceRepository $sourceRepository): Response
    {
        $sources = $sourceRepository->findBy(['product' => $product]);
        if (is_array($sources)) {
            $i = 0;
            $photos = [];
            foreach ($sources as $source) {
               $place = $source->getPlace();
               $photos[$i++] = $place->getPhoto(); 
            }            
        }
        return $this->render('product/show.html.twig', [
            'product' => $product,
            'sources' => $sources,
            'photos' => $photos
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
            $this->serviceImportYML->replaceOffer($product); 
            return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('product/edit.html.twig', [
            'product' => $product,
            'form' => $form
        ]);
    }

    /**
     * @Route("/delete/{id}", name="app_product_delete")
     */
    public function delete(Request $request, Product $product): Response
    {
        $productSku = $product->getSku();
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($product);
        $entityManager->flush();
        $fs = new Filesystem();
        $pathToFileYML = $this->getParameter('app.import_yml_directory') . $this->getParameter('app.import_product_file_name');
        $isFile = $fs->readlink($pathToFileYML, true);
        if ($isFile) {
            $this->serviceImportYML->removeOffer($productSku);
        }
        return $this->redirectToRoute('app_product_index');
    }

    /**
     * @Route("/orders/opt", name="app_order_index", methods={"GET"})
     */
    public function orders(Request $request, OrderRepository $ordersRepository): Response
    {
        
        return $this->render('order/index.html.twig', [
            'orders' => $ordersRepository->findAll()
        ]);
    }

    /**
     * @Route("/order/show/{id}", name="order_show", methods={"GET"})
     */
    public function showOrder(Order $order, OrderRepository $orderRepository): Response
    {
        return $this->render('order/show.html.twig', [
            'order' => $orderRepository->find($order)
        ]);
    }
}
