<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Comment;

class CommentsController extends AbstractController
{
    /**
     * @Route("/", name="comments")
     */
    public function index(Request $request) {
      if ($request->isMethod('POST')) {
        $name = $request->request->get('name');
        $comment = $request->request->get('comment');
        $entityManager = $this->getDoctrine()->getManager();

        $newComment = new Comment();
        $newComment->setName($name);
        $newComment->setComment($comment);
        $newComment->setCreatedAt(new \DateTime());

        $entityManager->persist($newComment);
        $entityManager->flush();
        return $this->redirectToRoute('comments');
      }
      $repository = $this->getDoctrine()->getRepository(Comment::class);
      $comments = $repository->findBy([], ['created_at' => 'DESC']);
      return $this->render('comments/index.html.twig', [
          'comments' => $comments

      ]);
    }
}
