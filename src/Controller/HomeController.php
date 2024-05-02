<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{

    //====================ROUTES CLIENT==============================
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('client/home/index.html.twig', [
        ]);
    }

    #[Route('/about', name: 'about_us')]
    public function about(): Response
    {
        return $this->render('client/home/about.html.twig', [
        ]);
    }

}
