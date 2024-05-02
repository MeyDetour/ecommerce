<?php

namespace App\Controller;

use App\Entity\Action;
use App\Entity\Comment;
use App\Entity\Product;
use App\Entity\ProductVariant;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/comment')]
class CommentController extends AbstractController
{
    #[Route('/{id}/new', name: 'comment_product_new')]
    public function index(ProductVariant $productVariant, Request $request, EntityManagerInterface $manager): Response
    {
        if (!$this->getUser() ||
            $this->getUser() != $productVariant->getCommand()->getAuthor() ||
            $productVariant->getProduct()->isCommentedBy($this->getUser())
        ) {
            return $this->redirectToRoute('profile_orders_show', ['id' => $productVariant->getCommand()->getId()]);
        }

        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setAuthor($this->getUser());
            $comment->setCreatedAt(new \DateTimeImmutable('now'));
            $comment->setProductVariant($productVariant);
            $comment->setProduct($productVariant->getProduct());
            $manager->persist($comment);
            $manager->flush();
            $action = new Action();
            $action->setComment($comment);
            $action->setCreatedAt(new \DateTimeImmutable('now'));
            $action->setAuthor($this->getUser());
            $action->setType('COMMENT');
            $manager->persist($action);
            $manager->flush();
            return $this->redirectToRoute('profile_comments');
        }

        return $this->render('/client/user/comment.html.twig', [
            'form' => $form->createView(),
            'prod' => $productVariant->getProduct()]);
    }

    #[Route('/edit/{id}', name: 'edit_comment')]
    public function edit(Comment $comment, Request $request, EntityManagerInterface $manager): Response
    {
        if (!$this->getUser() ||
            $this->getUser() != $comment->getAuthor()

        ) {
            return $this->redirectToRoute('profile_comments');
        }

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $manager->persist($comment);
            $manager->flush();
            return $this->redirectToRoute('profile_comments');
        }

        return $this->render('/client/user/comment.html.twig', [
            'form' => $form->createView(),
            'prod' => $comment->getProduct(),
                'comment'=>$comment
                ]
        );
    }

    #[Route('/del/{id}', name: 'del_comment')]
    public function remove(Comment $comment, EntityManagerInterface $manager): Response
    {
        if (!$this->getUser() ||
            $this->getUser() != $comment->getAuthor()
        ) {
            return $this->redirectToRoute('profile_comments');
        }
        $manager->remove($comment);
        $manager->flush();
        return  $this->redirectToRoute('profile_comments');
    }
}
