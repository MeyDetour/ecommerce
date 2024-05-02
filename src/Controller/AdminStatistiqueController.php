<?php

namespace App\Controller;

use App\Repository\ActionRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin')]
class AdminStatistiqueController extends AbstractController
{
    #[Route('/', name: 'app_statistique_admin')]
    public function index(ProductRepository $productRepository, ActionRepository $actionRepository): Response

    {
        return $this->render('admin/statistiques/index.html.twig', [
            'classement' => $productRepository->findBy([], ['popularity' => 'DESC']),
            'actions'=>$actionRepository->findBy([],['createdAt'=>'DESC'])
            ]);
    }

}
