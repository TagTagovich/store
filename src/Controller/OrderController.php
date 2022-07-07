<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Item;
use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/order")
 */
class OrderController extends AbstractController
{

    /**
     * @Route("/new", name="app_order_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $data = json_decode($request->query->get('json'), true);
        if (!empty($data)) {
            $dataToPHPArray = [];
            $order = new Order();
            $order->setNumber($data["number"]);
            $order->setAmount($data["sum"]);
            $order->setCreated($data["date"]);
            $item = new Item();
            for ($i=0; $i < count($data["items"]); $i++) { 
                $item = new Item();
                $item->setSku($data["items"][$i]["art"]);
                $item->setQuantity($data["items"][$i]["quantity"]);
                $item->setPrice($data["items"][$i]["price"]);
                $order->addItem($item);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($order);
            $entityManager->flush();

        }
            $resp = "successfull";    
            return new Response(json_encode($resp));
    }
}      