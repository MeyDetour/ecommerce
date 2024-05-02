<?php

namespace App\Service;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class CartService
{
    public function __construct(private ProductRepository $productRepository, private RequestStack $requestStack)
    {
    }

    public function addToCart(Product $product, $quantity)
    {
        $cart = $this->requestStack->getSession()->get('cart', []);
        if (isset($cart[$product->getId()])) {
            $cart[$product->getId()] += $quantity;
        } else {
            $cart[$product->getId()] = $quantity;
        }
        $this->requestStack->getSession()->set('cart', $cart);
    }

    public function setQuantity(Product $product, $quantity)
    {
        $cart = $this->requestStack->getSession()->get('cart', []);
        if (isset($cart[$product->getId()])) {
            $cart[$product->getId()] = $quantity;
        }
        $this->requestStack->getSession()->set('cart', $cart);
    }

    public function getQuantity(Product $product)
    {
        $cart = $this->requestStack->getSession()->get('cart', []);
        if (isset($cart[$product->getId()])) {
            return $cart[$product->getId()];
        }
        return 0;
    }

    public function getCart()
    {
        $cart = $this->requestStack->getSession()->get('cart', []);
        $data = [];
        foreach ($cart as $productId => $quantity) {
            $data[] = [
                "product" => $this->productRepository->find($productId),
                "quantity" => $quantity,
            ];
        }
        return $data;
    }

    public function removeOneToCart(Product $product)
    {
        $cart = $this->requestStack->getSession()->get('cart', []);
        if (isset($cart[$product->getId()]) && $cart[$product->getId()] > 0) {
            $cart[$product->getId()] -= 1;
            if ($cart[$product->getId()] == 0) {
                unset($cart[$product->getId()]);
            };
            $this->requestStack->getSession()->set('cart', $cart);

        }
    }

    public function removeProductToCart(Product $product)
    {
        $cart = $this->requestStack->getSession()->get('cart', []);
        if (isset($cart[$product->getId()])) {

            unset($cart[$product->getId()]);
            $this->requestStack->getSession()->set('cart', $cart);

        }
    }

    public function empty()
    {
        $this->requestStack->getSession()->remove('cart');
    }

    public function isEmpty()
    {
        $cart = $this->requestStack->getSession()->get('cart', []);
        if (empty($cart)) {
            return true;
        }
        return false;
    }

    public function isValid()
    {
        $cart = $this->getCart();
        foreach ($cart as $item) {
            $product = $item['product'];
            $quantity = $item['quantity'];
            if ($product->getQuantity() == 0 || $product->getQuantity() < $quantity) {
                return false;
            }
        }

        return true;
    }

    public function countProduct()
    {
        $cart = $this->requestStack->getSession()->get('cart', []);
        $count = 0;
        if (!empty($cart)) {
            foreach ($cart as $productId => $quantity) {
                $count += $quantity;
            }

        }
        return $count;
    }

    public function countPrice()
    {
        $cart = $this->getCart();
        $count = 0;
        foreach ($cart as $item) {
            $price = $item['product']->getPrice();
            $count += ($item['quantity'] * $price);
        }

        return $count;
    }
}

