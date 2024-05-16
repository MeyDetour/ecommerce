<?php

namespace App\Controller;

use App\Entity\Order;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/order')]
class AdminOrderController extends AbstractController
{

    #[Route('/', name: 'app_preparator_order')]
    #[Route('/delivry', name: 'app_order_delivry')]
    #[Route('/finish', name: 'app_order_finished')]
    public function orders( Request $request, OrderRepository $orderRepository): Response
    {
        $route = $request->attributes->get('_route');
       
        if($route == 'app_order_delivry'){
            return $this->render('preparator/index.html.twig', [
                'orderInDelivrance' => $orderRepository->findBy(['status' => 2, 'payStatus' => 1]),
            ]);
        }
        if($route == 'app_order_finished'){
            return $this->render('preparator/index.html.twig', [
                'orderFinished' => $orderRepository->findBy(['status' => 3, 'payStatus' => 1]),
            ]);
        }
        return $this->render('preparator/index.html.twig', [
            'orderToDelivry' => $orderRepository->findBy(['status' => 1, 'payStatus' => 1]),
              ]);
    }

    #[Route('/show/{id}', name: 'show_order_admin')]
    public function orderShow(Order $order): Response
    {
        return $this->render('preparator/show.html.twig', [
            'order' => $order]);
    }

    #[Route('/send/{id}', name: 'send_order_admin')]
    #[Route('/delivry/{id}', name: 'delivry_order_admin')]
    #[Route('/notsend/{id}', name: 'not_send_order_admin')]
    #[Route('/issus/{id}', name: 'issus_order_admin')]
    public function orderStatus(Order $order, EntityManagerInterface $manager, Request $request): Response
    {
        $route = $request->attributes->get('_route');
        if ($order->getPayStatus() != 1) {
            return $this->redirectToRoute('app_order_admin');
        }
        if ($route == 'send_order_admin') {
            foreach ($order->getItems() as $item) {
                $product = $item->getProduct();
                $quantity = $item->getQuantity();
                for ($i = 0; $i < $quantity; $i++) {
                    $productVariant = $product->getProductVariantFree();
                    $productVariant->setSell(true);
                    $productVariant->setCommand($item->getOrderCommand());
                    $manager->persist($productVariant);
                    $item->addProductVariant($productVariant);
                }
                $manager->persist($item);
            }

            $manager->flush();
            $order->setStatus(2);
        }
        elseif ($route == 'delivry_order_admin') {

            $order->setStatus(3);
        } elseif ($route == 'not_send_order_admin') {

            $order->setStatus(1);
        }else{
            $order->setStatus(4);
        }
        $manager->persist($order);
        $manager->flush();
        return $this->redirectToRoute('show_order_admin',['id'=>$order->getId()]);
    }


}
