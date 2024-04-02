<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Article;
use DateTime;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // On instancie Faker
        $faker = \Faker\Factory::create('fr_FR');

        // Création d'un nouvel article
        $article = new Article();
        $article->setTitle($faker->sentence);
        $article->setDescription($faker->sentences(5, true));
        $article->setAuthor($faker->userName);
        $article->setPublishingDate(new DateTime());

        // On génère le SQL pour insérer les données
        $manager->persist($article);
        // On exécute la requête
        $manager->flush();
    }
}
