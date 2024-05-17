<?php

namespace App\Controller;

use App\Repository\OrderListRepository;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{

    //====================ROUTES CLIENT==============================
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('client/home/index.html.twig', [
        ]);
    }

    #[Route('/calcul', name: 'app_calcul')]
    public function calcul(EntityManagerInterface $manager, ProductRepository $productRepository): Response
    {
        foreach ($productRepository->findAll() as $product) {
            $count = 0;
            foreach ($product->getProductVariants() as $variant) {
                if (!$variant->isSell()) {
                    $count++;
                }
            }
            $product->setQuantity($count);

            $manager->persist($product);

        }
        $manager->flush();

        return $this->render('client/home/index.html.twig', [
        ]);
    }

    #[Route('/reset', name: 'app_reset')]
    public function reset(EntityManagerInterface $manager, OrderListRepository $orderListRepository): Response
    {
        $last = $orderListRepository->findLastCreated();
        foreach ($last->getOrders() as $order) {
            $order->setStatus(1);
            foreach ($order->getProductVariants() as $variant) {
                $order->removeProductVariant($variant);
                $variant->setSell(false);
                $variant->setOrderItem(null);
                $manager->persist($variant);

            }
            $manager->persist($order);
        }
        $manager->flush();

        return $this->render('client/home/index.html.twig', [
        ]);
    }

    /* #[Route('/calcul', name: 'app_calcul')]
     public function calcul( EntityManagerInterface $manager,OrderRepository $orderRepository): Response
     {
       $order =  $orderRepository->find(91);
        foreach ($order->getProductVariants() as $variant){
            $order->removeProductVariant($variant);
            $manager->remove($variant);
            $manager->flush();
        }
        $manager->persist($order);
         $manager->flush();

         return $this->render('client/home/index.html.twig', [
         ]);
     }*/
    #[Route('/about', name: 'about_us')]
    public function about(): Response
    {
        return $this->render('client/home/about.html.twig', [
        ]);
    }

}
