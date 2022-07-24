<?php

namespace App\Controller;

use App\Entity\Auteur;
use App\Entity\User;
use App\Entity\Emprunteur;
use App\Entity\Genre;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DbTestController extends AbstractController
{
    #[Route('/db/test', name: 'app_db_test')]
    public function index(ManagerRegistry $doctrine): Response
    {
        //récupération du repository des users
        $repository = $doctrine->getRepository(User::class);
        // récupération de la liste complète de toutes les users
        $user = $repository->findAll();
        //inspection de la liste
        dump($user);

        //récupération du repository des emprenteurs
        $repository = $doctrine->getRepository(Emprunteur::class);
        // récupération de la liste complète de toutes les emprenteurs
        $emprunteur = $repository->findAll();
        //inspection de la liste
        dump($emprunteur);

        //récupération du repository des auteurs
        $repository = $doctrine->getRepository(Auteur::class);
        // récupération de la liste complète de toutes les auteurs
        $auteur = $repository->findAll();
        //inspection de la liste
        dump($auteur);

        //récupération du repository des genres
        $repository = $doctrine->getRepository(Genre::class);
        // récupération de la liste complète de toutes les genres
        $genre = $repository->findAll();
        //inspection de la liste
        dump($genre);


        exit();
    }
}
