<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Article;
use App\Entity\User;
use DateTime;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        public UserPasswordHasherInterface $passwordHasher
    ) {}

    public function load(ObjectManager $manager): void
    {
        // On instancie Faker
        $faker = \Faker\Factory::create('fr_FR');

        // Création d'un nouvel utilisateur
        $user = new User();
        $user->setEmail('test@test.com');
        $user->setPassword($this->passwordHasher->hashPassword($user, 'password'));

        // On génère le SQL pour insérer les données
        $manager->persist($user);
        // On exécute la requête
        $manager->flush();

        for ($i = 0; $i < 10; $i++) { 
            // Création d'un nouvel article
            $article = new Article();
            $article->setTitle($faker->sentence);
            $article->setDescription($faker->sentences(5, true));
            $article->setPublishingDate(new DateTime());
            $article->setPicture($faker->imageUrl(640, 480, 'animals', true));
            $article->setAuthor($user);

            // On génère le SQL pour insérer les données
            $manager->persist($article);
        }

        // On exécute la requête
        $manager->flush();
    }
}
