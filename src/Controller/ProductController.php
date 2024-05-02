<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\Service\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/product')]
class ProductController extends AbstractController
{
//====================ROUTES CLIENT==============================
    #[Route('/', name: 'app_product')]
    public function index(ProductRepository $productRepository, CategoryRepository $categoryRepository): Response
    {

        return $this->render('client/product/index.html.twig', [
            'products' => $productRepository->findBy(['visibility'=>true],['createdAt'=>'DESC',]),
            'categories' => $categoryRepository->findAll()
        ]);
    }

    #[Route('/{id}', name: 'show_product',priority: -1)]
    public function show(Product $product, CartService $cartService): Response
    {

        return $this->render('client/product/show.html.twig', [
            'prod' => $product,
            'quantityInCart'=> $cartService->getQuantity($product)
        ]);
    }




}
