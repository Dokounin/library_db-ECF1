<?php

namespace App\Controller;

use App\Entity\Auteur;
use App\Entity\Emprunt;
use App\Entity\User;
use App\Entity\Emprunteur;
use App\Entity\Genre;
use App\Entity\Livre;
use App\Repository\AuteurRepository;
use App\Repository\EmprunteurRepository;
use App\Repository\EmpruntRepository;
use App\Repository\GenreRepository;
use App\Repository\LivreRepository;
use App\Repository\UserRepository;
use DateTime;
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
    public function requests(UserRepository $userRepository, LivreRepository $livreRepository, GenreRepository $genreRepository, AuteurRepository $auteurRepository, ManagerRegistry $doctrine, EmprunteurRepository $emprunteurRepository, EmpruntRepository $empruntRepository): Response
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

        //! Les Emprunteurs :

        //* Requêtes de lecture :

        // la liste complète de tous les emprunteurs
        $emprunteurs = $emprunteurRepository->findAll();
        dump($emprunteurs);

        // les données de l'emprunteur dont l'id est `3`
        $emprunteur = $emprunteurRepository->find(3);
        dump($emprunteur);

        // les données de l'emprunteur qui est relié au user dont l'id est `3`
        $user = $userRepository->find(3);
        $emprunteur = $emprunteurRepository->findByUser($user);
        dump($emprunteur);

        // la liste des emprunteurs dont le nom ou le prénom contient le mot clé `foo`
        $emprunteur = $emprunteurRepository->findByKeyword('foo');
        dump($emprunteur);

        // la liste des emprunteurs dont le téléphone contient le mot clé `1234`
        $emprunteur = $emprunteurRepository->findByKeywordPhone('1234');
        dump($emprunteur);

        // la liste des emprunteurs dont la date de création est antérieure au 01/03/2021 exclu (c-à-d strictement plus petit)
        $date = DateTime::createFromFormat('Y-m-d H:i:s', '2021-03-01 00:00:00');
        $emprunteurs = $emprunteurRepository->findByPublishedAtBefore($date);
        dump($emprunteurs);

        // la liste des emprunteurs inactifs (c-à-d dont l'attribut `actif` est égal à `false`)
        $emprunteurs = $emprunteurRepository->findByIsNotActif('false');
        dump($emprunteurs);

        //! Les Emprunt :

        //* Requêtes de lecture :

        // la liste des 10 derniers emprunts au niveau chronologique 'en cours' ?

        // la liste des emprunts de l'emprunteur dont l'id est `2`
        $emprunteur = $emprunteurRepository->find(2);
        $emprunt = $empruntRepository->findByEmprunteur($emprunteur);
        dump($emprunt);

        // la liste des emprunts du livre dont l'id est `3`
        $livre = $livreRepository->find(3);
        $emprunt = $empruntRepository->findByLivre($livre);
        dump($emprunt);

        // la liste des emprunts qui ont été retournés avant le 01/01/2021
        $date = DateTime::createFromFormat('Y-m-d H:i:s', '2021-01-01 00:00:00');
        $emprunt = $empruntRepository->findByDateRetourBefore($date);
        dump($emprunt);

        // la liste des emprunts qui n'ont pas encore été retournés (c-à-d dont la date de retour est nulle)
        $emprunts = $empruntRepository->findByDateRetourIsNull();
        dump($emprunts);

        // les données de l'emprunt du livre dont l'id est `3` et qui n'a pas encore été retournés (c-à-d dont la date de retour est nulle)
        $livre = $livreRepository->find(3);
        $emprunt = $empruntRepository->findByLivreIsNull($livre);
        dump($emprunt);

        // * Requêtes de création :

        // récupération de l'Entity Manager
        $manager = $doctrine->getManager();

        // création d'un nouvel objet Emprunt
        $emprunt1 = new Emprunt();
        $date = DateTime::createFromFormat('Y-m-d H:i:s', '2020-12-01 16:00:00');
        $emprunt1->setDateEmprunt($date);
        $emprunt1->setDateRetour(null);
        $emprunteur = $emprunteurRepository->find(1);
        $emprunt1->setEmprunteur($emprunteur);
        $livre = $livreRepository->find(1);
        $emprunt1->setLivre($livre);


        $manager->flush();

        //* Requêtes de mise à jour :

        $emprunt2 = $empruntRepository->find(3);
        $date = DateTime::createFromFormat('Y-m-d H:i:s', '2020-05-01 10:00:00');
        $emprunt2->setDateRetour($date);

        $manager->flush();
        dump($emprunt2);

        // * Requêtes de suppression :

        $id = 42;
        $emprunt3 = $empruntRepository->find($id);

        if ($emprunt3) {
            // suppression d'un objet
            $manager->remove($emprunt3);
            $manager->flush();
        }
        dump($emprunt3);

        exit();
    }
}
