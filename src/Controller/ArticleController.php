<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ArticleController extends AbstractController
{
    #[Route('/article/add', name: 'app_add_article')]
    public function index(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $article = new Article();

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article->setAuthor($this->getUser());
            // On génère la requête SQL.
            $entityManager->persist($article);
            // On exécute la requête SQL.
            $entityManager->flush();
            // On ajoute un message de succès
            $this->addFlash('success', 'Votre article a bien été publié !');
            // On le redirige vers la page des articles
            return $this->redirectToRoute('app_home');
        }

        return $this->render('article/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
