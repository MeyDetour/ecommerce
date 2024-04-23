<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('admin/product')]
class AdminProductController extends AbstractController
{
//====================ROUTES CLIENT==============================

    #[Route('/', name: 'app_product_admin')]
    public function indexAdmin(ProductRepository $productRepository, CategoryRepository $categoryRepository): Response
    {
        return $this->render('admin/product/index.html.twig', [
            'products' => $productRepository->findAll(),
            'categories'=> $categoryRepository->findAll()
        ]);
    }
    #[Route('/new', name: 'new_product_admin')]
    public function create(Request $request, EntityManagerInterface $manager): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $product->setCreatedAt(new \DateTime('now'));
            $manager->persist($product);
            $manager->flush();
            return $this->redirectToRoute('images_product',['id'=>$product->getId()]);
        }
        return $this->render('admin/product/create.html.twig', [
            'formProd' => $form->createView(),
        ]);
    }

    #[Route('/edit/{id}', name: 'edit_product_admin')]
    public function edit( Product $product,Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
              $manager->persist($product);
            $manager->flush();
            return $this->redirectToRoute('images_product',['id'=>$product->getId()]);
        }
        return $this->render('admin/product/create.html.twig', [
            'formProd' => $form->createView(),
        ]);
    }
    #[Route('/{id}', name: 'del_product_admin')]
    public function delete(Product $product, EntityManagerInterface $manager): Response
    {

        $manager->remove($product);
        $manager->flush();
        return $this->redirectToRoute('app_product');
    }


    //====================ROUTES API ==============================


}
