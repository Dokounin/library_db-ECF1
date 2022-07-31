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
    public function requests(UserRepository $userRepository, LivreRepository $livreRepository, GenreRepository $genreRepository, AuteurRepository $auteurRepository): Response
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

        $livres = $livreRepository->findAll();
        dump($livres);

        $id = 1;
        $livre = $livreRepository->find($id);
        dump($livre);

        $livre = $livreRepository->findByKeyword('Lorem');
        dump($livre);


        $auteur = $auteurRepository->find(2);
        $livre = $auteur->getNom();
        $livre = $livreRepository->findByAuteur($auteur);
        dump($livre);


        $livres = $livreRepository->findByGenreKeyword('roman');
        dump($livres);
        
        foreach ($livres as $livre){
            dump($livre);

            $genres = $livre->getGenres();

            foreach($genres as $genre) {
                dump($genre);
            }
        }
        




        exit();
    }
}
