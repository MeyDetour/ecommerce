<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Product;
use App\Form\ImageType;
use App\Repository\ImageRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/image/')]
class AdminImageController extends AbstractController
{
    #[Route('/product/{id}', name: 'images_product')]
    public function addImageProduct(Product $product, Request $request, EntityManagerInterface $manager): Response
    {
        $image = new Image();
        $form = $this->createForm(ImageType::class, $image);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $image->setProduct($product);
            $manager->persist($image);
            $manager->flush();
        }
        return $this->redirectToRoute('add_images_product', ['id' => $product->getId()]);
    }

        #[Route('/delete/product/{id}', name: 'delete_image')]
        public function delImageProduct(Image $image, EntityManagerInterface $manager): Response
        {
            $id = $image->getProduct()->getId();
            $manager->remove($image);
            $manager->flush();
            return $this->redirectToRoute('add_images_product', ['id' => $id]);
        }

}