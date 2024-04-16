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

    #[Route('/article/{id}/modifier', name: 'app_edit_article')]
    public function edit(
        Request $request,
        EntityManagerInterface $entityManager,
        int $id,
    ) {
        // On récupère l'article en fonction de son id.
        $article = $entityManager->getRepository(Article::class)->find($id);
        if (!$article) {
            throw $this->createNotFoundException('Article introuvable');
        }

        // On créer un formulaire pour pouvoir modifier notre article
        $form = $this->createForm(ArticleType::class, $article);
        // On passe à notre formulaire les données de notre article + handleRequest
        $form->handleRequest($request);

        // On vérifie que le formulaire est soumis + est valide.
        if ($form->isSubmitted() && $form->isValid()) {
            // On vient mettre à jour les données de notre article dans notre BDD.
            $entityManager->persist($article);
            // On exécute la requête SQL.
            $entityManager->flush();
            // On ajoute un petit message flash de succès
            $this->addFlash('success', 'Votre article a bien été modifié !');
            // On redirige l'utilisateur vers la page de visualisation de cette article
            return $this->redirectToRoute('app_article', [
                'id' => $id,
            ]);
        }

        // On affiche notre formulaire d'édition d'article.
        return $this->render('article/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    // On créer une nouvelle route pour supprimer un article.
    #[Route('/article/{id}/supprimer', 'app_delete_article')]
    public function delete(int $id, EntityManagerInterface $entityManager)
    {
        // On récupère l'article en fonction de son id + on vérifie qu'il existe bien
        $article = $entityManager->getRepository(Article::class)->find($id);
        if (!$article) {
            throw $this->createNotFoundException('Article introuvable');
        }

        // On supprime l'article (+ les commentaires mais normalement ça se fait tout seul) depuis la base de données 
        $entityManager->remove($article);
        // /!\ On exeute la requête en base de données /!\
        $entityManager->flush();

        // On ajoute un message de succès
        $this->addFlash('success', 'Votre article a bien été supprimé !');
        // On redirige vers la page d'accueil.
        return $this->redirectToRoute('app_home');
    }
}
