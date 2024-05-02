<?php

namespace App\Controller;

use App\Entity\Like;
use App\Entity\Product;
use App\Repository\LikeRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LikeController extends AbstractController
{
    #[Route('/like/product/{id}', name: 'like_product')]
    public function index(Product $product, LikeRepository $likeRepository , EntityManagerInterface $manager): Response
    {
        $user = $this->getUser();
        if(!$user){
            return $this->json(['message'=>'no user']);
        }
        $like = $likeRepository->findOneBy(['author'=>$user,'product'=>$product]);
        if($like){
            $manager->remove($like);
            $manager->flush();
            return $this->json( ['isLiked'=>false]);
        }
        $like = new Like();
        $like->setAuthor($user);
        $like->setProduct($product);
        $manager->persist($like);
        $manager->flush();
        return $this->json(['isLiked'=>true]);
    }
}
