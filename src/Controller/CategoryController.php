<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CategoryController extends AbstractController
{
    #[Route('/category/{categoryName}', name: 'find_by_categorie')]
    public function findByCategory(ProductRepository $productRepository, CategoryRepository $categoryRepository, $categoryName): Response
    {
        $category = $categoryRepository->findOneBy(['name' => $categoryName]);
        $products = [];
        // Check if the category was found
        if ($category) {
            // Fetch the products associated with the category
            $products = $productRepository->findByCategory($category);
        }
        return $this->render('client/category/index.html.twig', [
            'name' => $categoryName,
            'products' => $products
        ]);
    }
}
