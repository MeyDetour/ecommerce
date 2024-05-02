<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Product;
use App\Entity\ProductVariant;
use App\Form\ImageType;
use App\Form\ProductType;
use App\Repository\CategoryRepository;
use App\Repository\ImageRepository;
use App\Repository\ProductRepository;
use App\Repository\ProductVariantRepository;
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
            'categories' => $categoryRepository->findAll()
        ]);
    }

    /**
     * @throws \Exception
     */
    #[Route('/new', name: 'new_product_admin')]
    public function create(Request $request, EntityManagerInterface $manager, ProductVariantRepository $productVariantRepository,ProductRepository $productRepository): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $product->setCreatedAt(new \DateTime('now'));
            $product->setPopularity(0);
            $manager->persist($product);
            $manager->flush();

            for ($i = 0; $i < $product->getQuantityWanted(); $i++) {
                $productVariant = new ProductVariant();
                $productVariant->setNumber($this->generateUniqueProductNumber($productVariantRepository));
                $productVariant->setProduct($product);
                $productVariant->setSell(false);
                $manager->persist($productVariant);
                $manager->flush();
            }


            return $this->redirectToRoute('add_images_product', ['id' => $product->getId()]);
        }
        return $this->render('admin/product/create.html.twig', [
            'formProd' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'add_images_product')]
    public function addImageProduct(Product $product, ImageRepository $imageRepository): Response
    {
        $image = new Image();
        $form = $this->createForm(ImageType::class, $image);
        return $this->render('/admin/product/image.html.twig', [
            'formImage' => $form->createView(),
            'prod' => $product,
            'images' => $imageRepository->findAll()
        ]);
    }

    #[Route('/edit/{id}', name: 'edit_product_admin')]
    public function edit(Product $product, Request $request, ProductVariantRepository $productVariantRepository ,EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        $cp = $product;
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($product);
            $manager->flush();
            if ($product->getQuantityWanted()> $cp->getQuantity()){
                for ($i = $cp->getQuantity(); $i <= $product->getQuantityWanted(); $i++) {
                    $productVariant = new ProductVariant();

                    $productVariant->setSell(false);
                    $productVariant->setNumber($this->generateUniqueProductNumber($productVariantRepository));
                    $productVariant->setProduct($product);
                    $manager->persist($productVariant);
                    $manager->flush();
                }
            }
            if($product->getQuantityWanted() < $cp->getQuantity()){
                for ($i = $cp->getQuantity(); $i > $product->getQuantityWanted(); $i--) {
                    $productVariant = $product->getProductVariantFree();

                    $manager->remove($productVariant);
                    $manager->flush();
                }

            }

            return $this->redirectToRoute('add_images_product', ['id' => $product->getId()]);
        }
        return $this->render('admin/product/create.html.twig', [
            'formProd' => $form->createView(),
            'prod' => $product
        ]);
    }

    #[Route('delete/{id}', name: 'del_product_admin')]
    public function delete(Product $product, EntityManagerInterface $manager): Response
    {
        $manager->remove($product);
        $manager->flush();
        return $this->redirectToRoute('app_product_admin');
    }


    //====================ROUTES API ==============================


}
