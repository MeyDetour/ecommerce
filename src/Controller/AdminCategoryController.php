<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
#[Route('/admin/category')]
class AdminCategoryController extends AbstractController
{

    #[Route('/', name: 'app_category_category')]
    public function indexAdmin(CategoryRepository $categoryRepository): Response
    {
        return $this->render('admin/category/index.html.twig', [
            'categories'=> $categoryRepository->findAll()
        ]);
    }
    #[Route('/new', name: 'new_category')]
    public function create(Request $request , EntityManagerInterface $manager): Response
    {

        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($category);
            $manager->flush();
            return $this->redirectToRoute('app_category');
        }
        return $this->render('admin/category/create.html.twig', [
            'formCat' =>$form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'del_category')]
    public function delete(Category $category , EntityManagerInterface $manager): Response
    {

        $manager->remove($category);
        $manager->flush();
        return $this->redirectToRoute('app_category');
    }


    //====================ROUTES API ==============================

}
