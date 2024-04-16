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
        // On récupère les 3 derniers articles en fonction de 
        // leur date de publication.
        $latestArticles = $articleRepository->findLatest();
        // On récupère les autres articles.
        $othersArticles = $articleRepository->findWithoutThreeLatest();
        // On retourne la vue avec les articles
        return $this->render('home/index.html.twig', [
            'latestArticles' => $latestArticles,
            'othersArticles' => $othersArticles
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
