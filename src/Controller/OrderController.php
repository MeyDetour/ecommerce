<?php

namespace App\Controller;

use App\Entity\Action;
use App\Entity\Adresse;
use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\OrderList;
use App\Entity\PayMethode;
use App\Form\AdresseType;
use App\Form\OrderType;
use App\Form\PayType;
use App\Repository\AdresseRepository;
use App\Repository\OrderListRepository;
use App\Repository\OrderRepository;
use App\Repository\PayMethodeRepository;
use App\Service\CartService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Snappy\Pdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class OrderController extends AbstractController
{
    #[Route('/order', name: 'order_adresse_payement')]
    public function index(CartService $cartService): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        if ($cartService->isEmpty() || !$cartService->isValid()) {
            return $this->redirectToRoute('app_cart');
        }
        $adresses = $this->getUser()->getAdresses();
        $methodes = $this->getUser()->getPayMethodes();

        $form = $this->createFormBuilder()
            ->add('addresseBilling', ChoiceType::class, [
                'label' => "Selectionnez l'adresse de facturation",
                'choices' => $adresses,
                'multiple' => false,
                'choice_label' => 'entireName',
                'choice_value' => "id",
                'data' => $adresses[0],
            ])
            ->add('addresseDelivery', ChoiceType::class, [
                'label' => "Choisissez l'adresse de livraison",
                'choices' => $adresses,
                'multiple' => false,
                'choice_label' => 'entireName',
                'choice_value' => "id",
                'data' => $adresses[0],
            ])
            ->add('payMethode', ChoiceType::class, [
                'label' => "Selectionnez le moyen de paiement",
                'choices' => $methodes,
                'multiple' => false,
                'expanded' => true,
                'choice_label' => 'cardNumber',
                'choice_value' => "id",
                'data' => $methodes[0],
            ])
            ->setMethod('POST')
            ->setAction($this->generateUrl("recap_order"))
            ->getForm();
        return $this->render('/client/order/selection.html.twig',
            ['form' => $form->createView()]);
    }

    #[Route('/order/delete/{id}', name: 'delete_order')]
    #[Route('/order/cancel/{id}', name: 'cancel_order')]
    public function delete(Request $request, Order $order, EntityManagerInterface $manager): Response
    {
        $route = $request->attributes->get('_route');
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        if ($order->getAuthor() != $user) {
            return $this->redirectToRoute('app_cart');
        }
        $this->deleteOrder($order, $manager);
        if ($route === 'cancel_order') {
            return $this->redirectToRoute('profile_orders');
        }
        return $this->redirectToRoute('order_adresse_payement');
    }

    private function deleteOrder($order, $manager)
    {
        foreach ($order->getItems() as $item) {
            foreach ($item->getProductVariants() as $variant) {
                $variant->setSell(false);
                $item->removeProductVariant($variant);
                $manager->persist($variant);
            }
            $manager->flush();
            $manager->remove($item);
            $manager->flush();
        }
        foreach ($order->getProductVariants() as $productVariant) {
            $order->removeProductVariant($productVariant);
            $variant->setCommand(null);
            $manager->persist($variant);
        }
        $manager->remove($order);
        $manager->flush();
    }


    #[Route('/cart/addorder/{id}', name: 'add_order_to_cart')]
    public function addOrderToCart(Order $order, EntityManagerInterface $manager, CartService $cartService): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        if ($order->getAuthor() != $user) {
            return $this->redirectToRoute('app_cart');
        }
        foreach ($order->getItems() as $item) {
            $cartService->addToCart($item->getProduct(), $item->getQuantity());
        }

        $this->deleteOrder($order, $manager);
        return $this->redirectToRoute('app_cart');


    }

    #[Route('/order/show/{id}', name: 'show_order')]
    public function showOrder(Order $order): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        return $this->render('/client/order/recap.html.twig', [
            'order' => $order

        ]);
    }


    #[Route('/order/recap', name: 'recap_order')]
    public function check(EntityManagerInterface $manager, OrderRepository $orderRepository, CartService $cartService, PayMethodeRepository $methodeRepository, Request $request, AdresseRepository $adresseRepository): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        $form = $request->get('form');
        $addresseDelivery = $adresseRepository->find($form['addresseDelivery']);
        $addresseBilling = $adresseRepository->find($form['addresseBilling']);
        $payMethode = $methodeRepository->find($form['payMethode']);

        if (!$addresseBilling || !$addresseDelivery || !$payMethode) {
            return $this->redirectToRoute('order_adresse_payement');
        }


        $nb = $this->generateUniqueOrderNumber($orderRepository);
        $order = new Order();
        $order->setCreatedAt(new \DateTimeImmutable());
        $order->setNumber($nb);
        $order->setAuthor($this->getUser());
        $order->setStatus(0);
        $order->setAdresse($addresseDelivery);
        $order->setPayment($payMethode);
        $order->setBillingAddresse($addresseBilling);
        $order->setPayStatus(0);
        $order->setTotal($cartService->countPrice());
        $manager->persist($order);
        $manager->flush();
        $action = new Action();
        $action->setCommand($order);
        $action->setCreatedAt(new \DateTimeImmutable('now'));
        $action->setAuthor($this->getUser());
        $action->setType('ORDER');
        $manager->persist($action);
        $manager->flush();
        foreach ($cartService->getCart() as $item) {
            $product = $item['product'];
            $product->updatePopularity();

            $quantity = $item['quantity'];

            $orderItem = new OrderItem();
            $orderItem->setProduct($product);
            $orderItem->setQuantity($quantity);
            $orderItem->setNumber($nb);
            $manager->persist($product);
            $manager->persist($orderItem);
            $manager->flush();

            $order->addItem($orderItem);
            $manager->persist($order);
            $manager->flush();

        }
        return $this->redirectToRoute('show_order', ['id' => $order->getId()]);
    }

    #[Route('/order/details/{id}', name: 'payement_is_valid')]
    public function payementIsValid(EntityManagerInterface $manager, OrderListRepository $orderListRepository, CartService $cartService, Order $order): Response
    {

        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        if (
            $order->getPayStatus() != 0
        ) {

            return $this->redirectToRoute('to_pay_order', ['id' => $order->getId()]);
        }
        $order->setPayStatus(1);
        $order->setStatus(1);
        $manager->persist($order);

        foreach ($order->getItems() as $item) {
            $product = $item->getProduct();
            $product->setQuantity($product->getQuantity() - 1);
            $manager->persist($item);
        }

        $lastOrderList = $orderListRepository->findLastCreated();
        $currentDate = new \DateTime('now');
        $shouldCreateNewOrderList = false;

        if ($lastOrderList) {
            $lastOrderDate = $lastOrderList->getDay();
            $interval = $currentDate->diff($lastOrderDate);

            if ($interval->days < 3 && $interval->invert == 0 && count($lastOrderList->getOrders()) <= 15) {
                $lastOrderList->addOrder($order);
            } else {
                $shouldCreateNewOrderList = true;
            }
        } else {
            $shouldCreateNewOrderList = true;
        }
      if ($shouldCreateNewOrderList) {
            $lastOrderList = new OrderList();
            $lastOrderList->setDay($currentDate);
            $lastOrderList->addOrder($order);
            $manager->persist($lastOrderList);
        }


        $manager->flush();
        $cartService->empty();
        return $this->redirectToRoute('profile_orders_show', ['id' => $order->getId()]);

    }


    #[Route('/order/details/wait/{id}', name: 'payement_wait_to_pay')]
    public function waitToPay(EntityManagerInterface $manager, CartService $cartService, Order $order): Response
    {

        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $cartService->empty();
        return $this->redirectToRoute('profile_orders_show', ['id' => $order->getId()]);
    }

    #[Route('/order/pdf/{id}', name: 'generate_pdf_order')]
    public function generatePdf(Pdf $knpSnappyPdf, Order $order): Response
    {
        if (!$this->getUser() or $order->getAuthor() != $this->getUser()) {
            return $this->redirectToRoute('profile_orders');
        }
        $html = $this->renderView('client/order/facturation.html.twig', array(
            'order' => $order
        ));
        return new PdfResponse(
            $knpSnappyPdf->getOutputFromHtml($html),
            'file.pdf'
        );
    }
}
