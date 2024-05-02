<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\OrderRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/user')]
class AdminUserController extends AbstractController
{

    #[Route('/', name: 'app_user_admin')]
    public function index(UserRepository $repository): Response
    {

        return $this->render('/admin/user/index.html.twig', [
            'users' => $repository->findBy([],['lastName'=>'ASC']),
        ]);
    }

    #[Route('/{id}', name: 'show_user_admin')]
    public function show(User $user, OrderRepository $orderRepository): Response
    {

        return $this->render('/admin/user/show.html.twig', [
            'user' => $user,
            'orders' => $orderRepository->findBy(['author' => $user], ['createdAt' => 'DESC'])
        ]);
    }


}
