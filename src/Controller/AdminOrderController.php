<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderList;
use App\Repository\IssuRepository;
use App\Repository\OrderListRepository;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use function PHPUnit\Framework\isEmpty;
use function PHPUnit\Framework\throwException;

#[Route('/admin/order')]
class AdminOrderController extends AbstractController
{
    //CONSTRUCT THE DAY ORDER LIST WITH LAST ORDER NOT DELIVRY
    // - RENDER THE ORDER LIST EMPTY
    // - REDIRECT TO RENDER THE ORDER LIST NOT EMPTY
    #[Route('/', name: 'app_preparator_order')]
    public function orderPreparator(EntityManagerInterface $manager, OrderRepository $orderRepository, OrderListRepository $orderListRepository): Response
    {
        $lastOrderList = $orderListRepository->findLastCreated();
        if (!$lastOrderList) {
            $orderList = new OrderList();

            $orderList->setDay(new \DateTime('now'));
            foreach ($orderRepository->findAll() as $order) {

                if ($order->getStatus() == 1) {
                    $orderList->addOrder($order);

                }
            }
            $manager->persist($orderList);
            $manager->flush();

            return $this->render('preparator/index.html.twig', [

            ]);
        }

        foreach ($orderListRepository->findAll() as $orderList) {
            if ($orderList != $lastOrderList) {
                foreach ($orderList->getOrders() as $order) {
                    if ($order->getStatus() == 1) {
                        $lastOrderList->addOrder($order);
                    }
                }
            }

        }
        $manager->persist($lastOrderList);
        $manager->flush();

        return $this->redirectToRoute('app_order_to_delivry', ['id' => $lastOrderList->getId()]);
    }

    // RENDER ORDER LIST WITH THE ID OF ORDER LIST
    // RENDER DELIVRY ORDER LIST AND FINISHED ORDER LIST
    #[Route('/todelivry/{id}', name: 'app_order_to_delivry')]
    #[Route('/delivry', name: 'app_order_delivry')]
    #[Route('/finish', name: 'app_order_finished')]
    public function orders(Request $request, ?OrderList $orderList, OrderRepository $orderRepository): Response
    {
        $route = $request->attributes->get('_route');

        if ($route == 'app_order_delivry') {
            return $this->render('preparator/index.html.twig', [
                'orderInDelivrance' => $orderRepository->findBy(['status' => 2, 'payStatus' => 1]),
            ]);
        }
        if ($route == 'app_order_finished') {
            return $this->render('preparator/index.html.twig', [
                'orderFinished' => $orderRepository->findBy(['status' => 3, 'payStatus' => 1]),
            ]);
        }


        return $this->render('preparator/index.html.twig', [
            'orderList' => $orderList
        ]);
    }

    //RENDER ONE ORDER
    #[Route('/show/{id}', name: 'show_order_admin')]
    public function orderShow(Order $order): Response
    {
        return $this->render('preparator/show.html.twig', [
            'order' => $order]);
    }
    #[Route('/information/{id}', name: 'information_order_admin')]
    public function orderInformation(Order $order): Response
    {
        return $this->render('preparator/information.html.twig', [
            'order' => $order]);
    }


    //REDIRECT TO SHOW THE LAST NO DELIVRY ORDER
    #[Route('/next/order/{id}', name: 'next_order_admin')]
    public function nextOrder(OrderList $orderList): Response
    {
        foreach ($orderList->getOrders() as $order) {
            if ($order->getStatus() == 1) {
                return $this->redirectToRoute('show_order_admin', ['id' => $order->getId()]);
            }
        }
        return $this->redirectToRoute('app_preparator_order');
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

            if ($order->getProductVariants()->isEmpty()) {
                foreach ($order->getItems() as $item) {
                    $product = $item->getProduct();
                    $quantity = $item->getQuantity();
                    for ($i = 0; $i < $quantity; $i++) {
                        $productVariant = $product->getProductVariantFree();
                        if(!$productVariant){
                            $manager->persist($order);
                            $manager->flush();
                           throw new \Exception('RUPTURE DE STOCK');
                        }
                        $productVariant->setSell(true);
                        $productVariant->setCommand($item->getOrderCommand());
                        $manager->persist($productVariant);
                        $item->addProductVariant($productVariant);
                    }
                    $manager->persist($item);
                }

                $manager->flush();
                return $this->redirectToRoute('show_order_admin', ['id' => $order->getId()]);

            } else {
                $order->setStatus(2);

                $manager->persist($order);

                $manager->flush();
                return $this->redirectToRoute('next_order_admin', ['id' => $order->getOrderList()->getId()]);

            }


        } elseif ($route == 'delivry_order_admin') {
            $order->setStatus(3);
            $manager->persist($order);

            $manager->flush();
            return $this->redirectToRoute('app_order_finished');

        } elseif ($route == 'not_send_order_admin') {

            $order->setStatus(1);
        } else {
            $order->setStatus(4);
        }
        return $this->redirectToRoute('show_order_admin', ['id' => $order->getId()]);
    }

}
