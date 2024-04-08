<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    // On définit la route pour la méthode index
    #[Route('/', 'app_home')]
    // On injecte le répertoire des articles
    public function index(ArticleRepository $articleRepository): Response
    {
        // On récupère tous les articles
        $articles = $articleRepository->findAll();

        // On retourne la vue avec les articles
        return $this->render('home/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    #[Route('/article/{id}', 'app_article')]
    public function article($id, ArticleRepository $articleRepository)
    {
        $article = $articleRepository->findOneBy(['id' => $id]);

        return $this->render('home/article.html.twig', [
            'article' => $article
        ]);
    }
}
