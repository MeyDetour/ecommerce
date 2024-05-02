<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\Product;
use App\Service\CartService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CartController extends AbstractController
{
    #[Route('/panier', name: 'app_cart')]
    public function showCart(CartService $cartService): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        return $this->render('/client/user/cart.html.twig', [
            'cart' => $cartService->getCart()
        ]);

    }

    #[Route('/product/add/{id}/{quantity}', name: 'add_to_cart')]
    #[Route('/cart/add/{id}/{quantity}', name: 'add_to_cart_from_show')]
    public function addToCart(EntityManagerInterface $manager, Request $request, CartService $cartService, Product $product, $quantity): Response
    {
        $route = $request->attributes->get('_route');
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $product->updatePopularity();
        $manager->persist($product);
        $manager->flush();
        $cartService->addToCart($product, $quantity);
        if ($route == 'add_to_cart_from_show') {
            return $this->redirectToRoute('show_product', ['id' => $product->getId()]);
        }

        return $this->redirectToRoute('app_cart');


    }


    #[Route('/cart/removee/{id}/1', name: 'remove_one_to_cart_from_cart')]
    public function removeOneToCart(CartService $cartService, Product $product): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        $cartService->removeOneToCart($product);
        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cart/remove/{id}', name: 'remove_product_to_cart')]
    public function removeProductToCart(CartService $cartService, Product $product): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        $cartService->removeProductToCart($product);
        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cart/empty', name: 'empty_cart')]
    public function emptyCart(CartService $cartService): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        $cartService->empty();
        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cart/set/{id}/{quantity}', name: 'set_quantity_cart')]
    public function setQuantity(Product $product, $quantity, CartService $cartService): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        $cartService->setQuantity($product, $quantity);
        return $this->redirectToRoute('app_cart');
    }



}
