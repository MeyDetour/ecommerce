<?php

namespace App\Controller;

use App\Entity\Issu;
use App\Entity\Order;
use App\Entity\Product;
use App\Repository\IssuRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/order')]
class AdminOrderIssuController extends AbstractController
{
    #[Route('/issu', name: 'app_order_issu')]
    public function orderISsu(IssuRepository $repository): Response
    {
        return $this->render('preparator/probleme.html.twig', [
            'issus' => $repository->findBy([], ['createdAt' => 'DESC'])]);
    }

    #[Route('/issu/new', name: 'new_order_issu')]
    public function newIssu(ProductRepository $productRepository): Response
    {
        return $this->render('preparator/probleme.html.twig', [
            'products' => $productRepository->findAll()]);
    }

    #[Route('/issu/product/{id}', name: 'product_order_issu')]
    public function issuOnProduct(Product $product): Response
    {
        return $this->render('preparator/probleme.html.twig', [
            'product' => $product]);
    }

    #[Route('/defaut/product/{id}', name: 'product_order_defaut')]
    #[Route('/rupture/product/{id}', name: 'product_order_rupture')]
    public function createIssu(Product $product, EntityManagerInterface $manager , Request $request): Response
    {
        $route = $request->attributes->get('_route');

        $probleme = new Issu();
        $date = new \DateTimeImmutable('now');
        $probleme->setCreatedAt($date);
        $probleme->setProduct($product);
        if($route == 'product_order_defaut'){
            $probleme->setMessage('Default de produit sur ' . $product->getName() . ' signalé le ' . $date->format('d-m-Y'));

        }else{
            $probleme->setMessage('Rupture de stock pour le produit ' . $product->getName() . ' signalé le ' . $date->format('d-m-Y'));

        }
        $manager->persist($probleme);
      $manager->flush();
        return $this->redirectToRoute('app_order_issu');
    }

}
