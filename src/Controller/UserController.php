<?php

namespace App\Controller;

use App\Entity\Adresse;
use App\Entity\Order;
use App\Entity\PayMethode;
use App\Form\AdresseType;
use App\Form\PayType;
use App\Form\RegistrationFormType;
use App\Form\UserType;
use App\Repository\AdresseRepository;
use App\Repository\OrderRepository;
use App\Repository\UserRepository;
use App\Service\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/profile')]
class UserController extends AbstractController
{

//====================ROUTES CLIENT==============================
    #[Route('/', name: 'app_profil')]
    public function profil(Request $request , EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(UserType::class, $this->getUser());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

          $manager->persist($this->getUser());
          $manager->flush();
          return $this->redirectToRoute('app_profil');
        }
        return $this->render('/client/user/show.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/adresse', name: 'profile_adresse')]
    public function adresse(EntityManagerInterface $manager, Request $request): Response
    {


        return $this->render('/client/user/adresse.html.twig', [

        ]);
    }

    #[Route('/envies', name: 'profile_envies')]
    public function envies(EntityManagerInterface $manager, Request $request): Response
    {


        return $this->render('/client/user/like.html.twig', [

        ]);
    }

    #[Route('/adresse/new', name: 'profile_adresse_new')]
    public function adresseNew(EntityManagerInterface $manager, Request $request): Response
    {

        $adresse = new Adresse();
        $form = $this->createForm(AdresseType::class, $adresse);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $adresse->setOwner($this->getUser());
            $manager->persist($adresse);
            $manager->flush();
            return $this->redirectToRoute('profile_adresse');
        }
        return $this->render('/client/user/adresse.html.twig', [
            'form' => $form->createView(),

        ]);
    }

    #[Route('/adresse/{id}', name: 'profile_adresse_edit')]
    public function adresseEdit(Adresse $adresse, EntityManagerInterface $manager, Request $request,): Response
    {


        $form = $this->createForm(AdresseType::class, $adresse);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $adresse->setOwner($this->getUser());
            $manager->persist($adresse);
            $manager->flush();
            return $this->redirectToRoute('profile_adresse');
        }
        return $this->render('/client/user/adresse.html.twig', [
            'form' => $form->createView(),
            'adresse' => $adresse
        ]);
    }

    #[Route('/payement', name: 'profile_payement')]
    public function payement(): Response
    {


        return $this->render('/client/user/pay.html.twig.twig', [

        ]);
    }

    #[Route('/payement/new', name: 'profile_payement_new')]
    public function payementNew(EntityManagerInterface $manager, Request $request, AdresseRepository $adresseRepository): Response
    {

        $methode = new PayMethode();
        $form = $this->createForm(PayType::class, $methode);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $methode->setOwner($this->getUser());
            $manager->persist($methode);
            $manager->flush();
            return $this->redirectToRoute('profile_payement');
        }
        return $this->render('/client/user/pay.html.twig.twig', [
            'form' => $form->createView(),

        ]);
    }

    #[Route('/payement/edit/{id}', name: 'profile_payement_edit')]
    public function payementEdit(EntityManagerInterface $manager, PayMethode $methode, Request $request, AdresseRepository $adresseRepository): Response
    {
        if ($this->getUser() != $methode->getOwner()) {
            return $this->redirectToRoute('app_cart');
        }
        $form = $this->createForm(PayType::class, $methode);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $methode->setOwner($this->getUser());
            $manager->persist($methode);
            $manager->flush();
            return $this->redirectToRoute('profile_payement');
        }
        return $this->render('/client/user/pay.html.twig.twig', [
            'form' => $form->createView(),
            'methode' => $methode
        ]);
    }

    #[Route('/commandes', name: 'profile_orders')]
    public function orders(OrderRepository $orderRepository): Response
    {
        return $this->render('/client/user/orders.html.twig', [
            'orderFinished' => $orderRepository->findBy(['status' => 3, 'payStatus' => 1, 'author' => $this->getUser()], ['createdAt' => 'DESC']),
            'orderToDelivry' => $orderRepository->findBy(['status' => 1, 'payStatus' => 1, 'author' => $this->getUser()], ['createdAt' => 'DESC']),
            'ordermInDelivrance' => $orderRepository->findBy(['status' => 2, 'payStatus' => 1, 'author' => $this->getUser()], ['createdAt' => 'DESC']),
            'orderUnpayed' => $orderRepository->findBy(['payStatus' => 0, 'author' => $this->getUser()], ['createdAt' => 'DESC']),

        ]);
    }

    #[Route('/commande/{id}', name: 'profile_orders_show')]
    public function orderShow(Order $order): Response
    {

            $isValid = true;
        if ($order->getPayStatus() == 0) {
            foreach ($order->getItems() as $item) {
                if ($item->getQuantity() > $item->getProduct()->getQuantity()) {
                    $isValid = false;
                }
            }
        }

        return $this->render('/client/user/orders.html.twig', [
            'order' => $order,
            'isValid' => $isValid]);
    }

    #[Route('/commentaires', name: 'profile_comments')]
    public function comments(): Response
    {
        return $this->render('/client/user/comment.html.twig', [
        ]);
    }
}
