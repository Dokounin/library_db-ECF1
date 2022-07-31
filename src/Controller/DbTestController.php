<?php

namespace App\Controller;

use App\Entity\Auteur;
use App\Entity\Emprunt;
use App\Entity\User;
use App\Entity\Emprunteur;
use App\Entity\Genre;
use App\Entity\Livre;
use App\Repository\AuteurRepository;
use App\Repository\GenreRepository;
use App\Repository\LivreRepository;
use App\Repository\UserRepository;
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

        //récupération du repository des livres
        $repository = $doctrine->getRepository(Livre::class);
        // récupération de la liste complète de toutes les livres
        $livre = $repository->findAll();
        //inspection de la liste
        dump($livre);

        //récupération du repository des emprunts
        $repository = $doctrine->getRepository(Emprunt::class);
        // récupération de la liste complète de toutes les emprunts
        $emprunt = $repository->findAll();
        //inspection de la liste
        dump($emprunt);




        exit();
    }


    //todo          Les requêtes

    #[Route('/db/test/requests', name: 'app_db_test_requests')]
    public function requests(UserRepository $userRepository, LivreRepository $livreRepository, GenreRepository $genreRepository, AuteurRepository $auteurRepository, ManagerRegistry $doctrine): Response
    {
        //! Les utilisateurs

        // récupération de la liste complète de toutes les users
        $users = $userRepository->findAll();
        //inspection de la liste
        dump($users);

        // récupération d'un objet à partir de son id
        $id = 1;
        $user = $userRepository->find($id);
        dump($user);

        // récupération d'un objet à partir de mots cles
        $user = $userRepository->findByKeyword('foo.foo@example.com');
        dump($user);

        $role = 'ROLE_EMPRUNTEUR';
        $users = $userRepository->findByRoles($role);
        dump($users);

        //! Les livres

        //* Requêtes de lecture :

        // la liste complète de tous les livres
        $livres = $livreRepository->findAll();
        dump($livres);

        // les données du livre dont l'id est `1`
        $id = 1;
        $livre = $livreRepository->find($id);
        dump($livre);

        // la liste des livres dont le titre contient le mot clé `lorem`
        $livre = $livreRepository->findByKeyword('Lorem');
        dump($livre);

        // la liste des livres dont l'id de l'auteur est `2`
        $auteur = $auteurRepository->find(2);
        $livre = $auteur->getNom();
        $livre = $livreRepository->findByAuteur($auteur);
        dump($livre);

        // la liste des livres dont le genre contient le mot clé `roman`
        $livres = $livreRepository->findByGenreKeyword('roman');
        dump($livres);

        //* Listez tout les livres avec leur genre
        // foreach ($livres as $livre) {
        //     dump($livre);

        //     $genres = $livre->getGenres();

        //     foreach ($genres as $genre) {
        //         dump($genre);
        //     }
        // }


        // * Requêtes de création :

        // création d'un nouvel objet Livre
        $livre1 = new Livre();
        $livre1->setTitre('Totum autem id externum');
        $livre1->setAnneEdition(2020);
        $livre1->setNombresPages(300);
        $livre1->setCodeIsbn('9790412882714');
        $auteur = $auteurRepository->find(2);
        $livre1->setAuteur($auteur);
        $genre = $genreRepository->find(6);
        $livre1->addGenre($genre);

        // récupération de l'Entity Manager
        $manager = $doctrine->getManager();
        // demande d'enregistrement de l'objet dans la BDD
        $manager->persist($livre1);
        $manager->flush();
        dump($livre1);

        // * Requêtes de mise à jour :

        // modification d'un objet

        // recuperation d'un livre par son ID
        $livre2 = $livreRepository->find(2);
        // changment de titre 
        $livre2->setTitre('Aperiendum est igitur');
        // recuperation de genre par son ID
        $genre = $genreRepository->find(2);
        // suppression genre
        $livre2->removeGenre($genre);
        // recuperation de genre par son ID
        $genre = $genreRepository->find(5);
        // Ajout nouvelle genre
        $livre2->addGenre($genre);

        // enregistrement de la modification dans la BDD
        $manager->flush();
        
        dump($livre2);

        // * Requêtes de suppression :
        $id = 123;
        $livre3 = $livreRepository->find($id);

        if ($livre3) {
            // suppression d'un objet
            $manager->remove($livre3);
            $manager->flush();
        }
        dump($livre3);

        exit();
    }
}
