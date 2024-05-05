<?php

namespace App\Controller;

use App\Entity\Order;
use Stripe\Charge;
use Stripe\Checkout\Session;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use Stripe\Terminal\Location;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class StripeController extends AbstractController
{
    #[Route('/stripe/success/{id}', name: 'app_stripe_success')]
    public function successAction($id): Response
    {
          return $this->redirectToRoute('payement_is_valid',['id'=>$id]);
    }

    #[Route('/stripe/cancel/{id}', name: 'app_stripe_cancel')]
    public function cancelToRecap($id): Response
    {
        return $this->redirectToRoute('show_order',['id'=>$id]);
    }
    #[Route('/stripe/profil/cancel/{id}', name: 'app_stripe_cancel_to_profil')]
    public function cancelToProfil($id): Response
    {
        return $this->redirectToRoute('profile_orders_show',['id'=>$id]);
    }

    #[Route('/stripe/order/pay/{id}', name: 'to_pay_order_from_profil')]
    #[Route('/stripe/pay/{id}', name: 'to_pay_order')]
    public function createCharge(Order $order, Request $request)
    {
        Stripe::setApiKey($_ENV['STRIPE_SECRET']);
        $route = $request->attributes->get('_route');

        $cancelUrl = $this->generateUrl('app_stripe_cancel',  ['id'=>$order->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
        if($route =='to_pay_order_from_profil' ){
            $cancelUrl = $this->generateUrl('app_stripe_cancel_to_profil',  ['id'=>$order->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
        }

        $products = [];
        foreach ($order->getItems() as $item) {
            $products[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $item->getProduct()->getPrice() * 100, // Amount in cents
                    'product_data' => [
                        'name' => $item->getProduct()->getName(),
                    ],
                ],
                'quantity' => $item->getQuantity(),
            ];
        }

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $products,
            'mode' => 'payment',
            'success_url' => $this->generateUrl('app_stripe_success', ['id'=>$order->getId()],UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' =>$cancelUrl ,
            "customer_email" => $order->getAuthor()->getEmail(),
            'payment_intent_data' => [
                'description' => 'Commande n°' . $order->getNumber(), // Description du paiement
            ],
            'metadata' => [
                'order_id' => $order->getId(),
                'order_date' => $order->getCreatedAt()->format('Y-m-d H:i:s')
            ],
        ]);

        $this->addFlash(
            'success',
            'Payment Successful!'
        );
        return new RedirectResponse($session->url, 302);
    }
}
