<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
#[Route('/admin/user')]
class AdminUserController extends AbstractController
{

    #[Route('/', name: 'app_user')]
    public function index(UserRepository $repository): Response
    {

        return $this->render('/admin/user/index.html.twig', [
            'users' => $repository->findAll(),
        ]);
    }




}
