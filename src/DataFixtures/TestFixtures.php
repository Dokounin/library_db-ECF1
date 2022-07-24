<?php

namespace App\DataFixtures;

use App\Entity\Auteur;
use App\Entity\Emprunt;
use App\Entity\User;
use App\Entity\Emprunteur;
use App\Entity\Genre;
use App\Entity\Livre;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as FakerFactory;
use Faker\Generator as FakerGenerator;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class TestFixtures extends Fixture
{
    public function __construct(ManagerRegistry $doctrine, UserPasswordEncoderInterface $encoder)
    {
        $this->doctrine = $doctrine;
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = FakerFactory::create("fr_FR");
        $this->loadUser($manager, $faker);
        $this->loadEmprunteur($manager, $faker);
        $this->loadAuteur($manager, $faker);
        $this->loadGenre($manager, $faker);
        $this->loadLivre($manager, $faker);
        $this->loadEmprunt($manager, $faker);
    }

    public function loadUser(ObjectManager $manager, FakerGenerator $faker): void
    {
        $userDatas =
            [
                [
                    "email" => "admin@example.com",
                    "roles" => ['ROLE_ADMIN'],
                    "password" => "$2y$10$/H2ChUxriH.0Q33g3EUEx.S2s4j/rGJH2G88jK9nCP60GbUW8mi5K",
                    "enabled" => true,
                    "created_at" => DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2020-01-01 09:00:00'),
                    "updated_at" => DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2020-01-01 09:00:00'),
                ],
                [
                    "email" => "foo.foo@example.com",
                    "roles" => ['ROLE_EMPRUNTEUR'],
                    "password" => "$2y$10$/H2ChUxriH.0Q33g3EUEx.S2s4j/rGJH2G88jK9nCP60GbUW8mi5K",
                    "enabled" => true,
                    "created_at" => DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2020-01-01 10:00:00'),
                    "updated_at" => DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2020-01-01 10:00:00'),
                ],
                [
                    "email" => "bar.bar@example.com",
                    "roles" => ['ROLE_EMPRUNTEUR'],
                    "password" => "$2y$10$/H2ChUxriH.0Q33g3EUEx.S2s4j/rGJH2G88jK9nCP60GbUW8mi5K",
                    "enabled" => false,
                    "created_at" => DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2020-02-01 11:00:00'),
                    "updated_at" => DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2020-05-01 12:00:00'),
                ],
                [
                    "email" => "baz.baz@example.com",
                    "roles" => ['ROLE_EMPRUNTEUR'],
                    "password" => "$2y$10$/H2ChUxriH.0Q33g3EUEx.S2s4j/rGJH2G88jK9nCP60GbUW8mi5K",
                    "enabled" => true,
                    "created_at" => DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2020-03-01 12:00:00'),
                    "updated_at" => DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2020-03-01 12:00:00'),
                ],

            ];

        foreach ($userDatas as $userData) {
            $user = new User();
            $user->setEmail($userData['email']);
            $user->setRoles($userData['roles']);
            $user->setPassword($userData['password']);
            $user->setEnabled($userData['enabled']);
            $user->setCreatedAt($userData['created_at']);
            $user->setUpdatedAt($userData['updated_at']);

            $manager->persist($user);
        }



        // boucle pour créer 100 user aleatoire via faker
        for ($i = 0; $i < 100; $i++) {

            $user = new User();
            $user->setEmail($faker->email());
            $user->setRoles(['ROLE_EMPRUNTEUR']);
            $password = $this->encoder->encodePassword($user, '123');
            $user->setPassword($password);
            $user->setEnabled($faker->boolean());

            $date = $faker->dateTimeBetween('-6 month', '+6 month');
            // format : YYYY-mm-dd HH:ii:ss
            $date = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', "2022-{$date->format('m-d H:i:s')}");
            // si la gestion de la date est trop compliquée, voici une alternative mais l'année changera en fonction de quand vous lancer le chargement des fixtures
            // $date = $faker->dateTimeThisYear();
            // $date = DateTimeImmutable::createFromInterface($date);
            $user->setCreatedAt($date);
            $date = $faker->dateTimeBetween('-6 month', '+6 month');
            // format : YYYY-mm-dd HH:ii:ss
            $date = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', "2022-{$date->format('m-d H:i:s')}");
            // si la gestion de la date est trop compliquée, voici une alternative mais l'année changera en fonction de quand vous lancer le chargement des fixtures
            // $date = $faker->dateTimeThisYear();
            // $date = DateTimeImmutable::createFromInterface($date);
            $user->setUpdatedAt($date);

            $manager->persist($user);
        }
        $manager->flush();
    }

    public function loadEmprunteur(ObjectManager $manager, FakerGenerator $faker): void
    {
        $repository = $this->doctrine->getRepository(User::class);
        $users = $repository->findAll();


        $emprunteurDatas = [
            [
                'nom' => 'foo',
                'prenom' => 'foo',
                'tel' => '123456789',
                'actif' => true,
                "created_at" => DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2020-01-01 10:00:00'),
                "updated_at" => DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2020-01-01 10:00:00'),
                "user" => $users[1],
            ],
            [
                'nom' => 'bar',
                'prenom' => 'bar',
                'tel' => '123456789',
                'actif' => false,
                "created_at" => DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2020-02-01 11:00:00'),
                "updated_at" => DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2020-05-01 12:00:00'),
                "user" => $users[2],
            ],
            [
                'nom' => 'baz',
                'prenom' => 'baz',
                'tel' => '123456789',
                'actif' => true,
                "created_at" => DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2020-03-01 12:00:00'),
                "updated_at" => DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2020-03-01 12:00:00'),
                "user" => $users[3],
            ],
        ];

        foreach ($emprunteurDatas as $emprunteurData) {
            $emprunteur = new Emprunteur();
            $emprunteur->setNom($emprunteurData['nom']);
            $emprunteur->setPrenom($emprunteurData['prenom']);
            $emprunteur->setTel($emprunteurData['tel']);
            $emprunteur->setActif($emprunteurData['actif']);
            $emprunteur->setCreatedAt($emprunteurData['created_at']);
            $emprunteur->setUpdatedAt($emprunteurData['updated_at']);
            $emprunteur->setUser($emprunteurData['user']);

            $manager->persist($emprunteur);
        }

        // boucle pour créer 100 empreunteur aleatoire via faker
        for ($i = 4; $i < 104; $i++) {

            $emprunteur = new Emprunteur();
            $emprunteur->setNom($faker->lastName());
            $emprunteur->setPrenom($faker->firstNameMale());
            $emprunteur->setTel($faker->phoneNumber());
            $emprunteur->setActif($faker->boolean());
            $emprunteur->setUser($users[$i]);

            $date = $faker->dateTimeBetween('-6 month', '+6 month');
            // format : YYYY-mm-dd HH:ii:ss
            $date = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', "2022-{$date->format('m-d H:i:s')}");
            // si la gestion de la date est trop compliquée, voici une alternative mais l'année changera en fonction de quand vous lancer le chargement des fixtures
            // $date = $faker->dateTimeThisYear();
            // $date = DateTimeImmutable::createFromInterface($date);
            $emprunteur->setCreatedAt($date);
            $date = $faker->dateTimeBetween('-6 month', '+6 month');
            // format : YYYY-mm-dd HH:ii:ss
            $date = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', "2022-{$date->format('m-d H:i:s')}");
            // si la gestion de la date est trop compliquée, voici une alternative mais l'année changera en fonction de quand vous lancer le chargement des fixtures
            // $date = $faker->dateTimeThisYear();
            // $date = DateTimeImmutable::createFromInterface($date);
            $emprunteur->setUpdatedAt($date);





            $manager->persist($emprunteur);
        }

        $manager->flush();
    }

    public function loadAuteur(ObjectManager $manager, FakerGenerator $faker): void
    {
        $auteurDatas =
            [
                [
                    'nom' => 'auteur inconnu',
                    'prenom' => '',
                ],
                [
                    'nom' => 'Cartier ',
                    'prenom' => 'Hugues',
                ],
                [
                    'nom' => 'Lambert',
                    'prenom' => 'Armand',
                ],
                [
                    'nom' => 'Moitessier',
                    'prenom' => 'Thomas',
                ],
            ];

        foreach ($auteurDatas as $auteurData) {
            $auteur = new Auteur();
            $auteur->setNom($auteurData['nom']);
            $auteur->setPrenom($auteurData['prenom']);

            $manager->persist($auteur);
        }

        // boucle pour créer 500 auteur aleatoire via faker
        for ($i = 0; $i < 500; $i++) {
            $auteur = new Auteur();
            $auteur->setNom($faker->lastName());
            $auteur->setPrenom($faker->firstNameMale());

            $manager->persist($auteur);
        }

        $manager->flush();
    }

    public function loadGenre(ObjectManager $manager, FakerGenerator $faker): void
    {
        $genreDatas =
            [
                'poésie',
                'nouvelle',
                'roman historique',
                "roman d'amour",
                "roman d'aventure",
                'science-fiction',
                'fantasy',
                'biographie',
                'conte',
                'témoignage',
                'théâtre',
                'essai',
                'journal intime',
            ];

        foreach ($genreDatas as $genreData) {
            $genre = new Genre();
            $genre->setNom($genreData);
            $genre->setDescription(null);

            $manager->persist($genre);
        }

        $manager->flush();
    }

    public function loadLivre(ObjectManager $manager, FakerGenerator $faker): void
    {
        $repository = $this->doctrine->getRepository(Auteur::class);
        $auteurs = $repository->findAll();

        $repository = $this->doctrine->getRepository(Genre::class);
        $genres = $repository->findAll();





        $livreDatas = [
            [
                'titre' => 'Lorem ipsum dolor sit amet',
                'anne_edition' => 2010,
                'nombre_pages' => 100,
                'code_isbn' => '9785786930024',
                'auteur' => $auteurs[0],
                'genre' => $genres[0],
            ],
            [
                'titre' => 'Consectetur adipiscing elit',
                'anne_edition' => 2011,
                'nombre_pages' => 150,
                'code_isbn' => '9783817260935',
                'auteur' => $auteurs[1],
                'genre' => $genres[1],
            ],
            [
                'titre' => 'Mihi quidem Antiochum',
                'anne_edition' => 2012,
                'nombre_pages' => 200,
                'code_isbn' => '9782020493727',
                'auteur' => $auteurs[2],
                'genre' => $genres[2],
            ],
            [
                'titre' => 'Quem audis satis belle ',
                'anne_edition' => 2013,
                'nombre_pages' => 250,
                'code_isbn' => '9794059561353',
                'auteur' => $auteurs[3],
                'genre' => $genres[3],
            ],
        ];

        foreach ($livreDatas as $livreData) {
            $livre = new Livre();
            $livre->setTitre($livreData['titre']);
            $livre->setAnneEdition($livreData['anne_edition']);
            $livre->setNombresPages($livreData['nombre_pages']);
            $livre->setCodeIsbn($livreData['code_isbn']);
            $livre->setAuteur($livreData['auteur']);
            $livre->addGenre($livreData['genre']);

            $manager->persist($livre);
        }

        for ($i = 0; $i < 1000; $i++) {
            $livre = new Livre();
            $livre->setTitre($faker->words(3, true));
            $countYears = random_int(1900, 2022);
            $livre->setAnneEdition($countYears);
            $countPages = random_int(100, 1000);
            $livre->setNombresPages($countPages);
            $livre->setCodeIsbn($faker->isbn13());

            $auteur = $faker->randomElements($auteurs)[0];
            $livre->setAuteur($auteur);

            $genre = $faker->randomElements($genres)[0];
            $livre->addGenre($genre);

            $manager->persist($livre);
        }

        $manager->flush();
    }

    public function loadEmprunt(ObjectManager $manager, FakerGenerator $faker): void
    {
        $repository = $this->doctrine->getRepository(Emprunteur::class);
        $emprunteurs = $repository->findAll();

        $repository = $this->doctrine->getRepository(Livre::class);
        $livres = $repository->findAll();

        $empruntDatas =
            [
                [
                    "date_emprunt" => DateTimeImmutable::createFromFormat('Y-m-d H:i:s', "2020-02-01 10:00:00"),
                    "date_retour" => DateTimeImmutable::createFromFormat('Y-m-d H:i:s', "2020-03-01 10:00:00"),
                    "emprunteur" => $emprunteurs[0],
                    "livre" => $livres[0],
                ],
                [
                    "date_emprunt" => DateTimeImmutable::createFromFormat('Y-m-d H:i:s', "2020-03-01 10:00:00"),
                    "date_retour" => DateTimeImmutable::createFromFormat('Y-m-d H:i:s', "2020-04-01 10:00:00"),
                    "emprunteur" => $emprunteurs[1],
                    "livre" => $livres[1],
                ],
                [
                    "date_emprunt" => DateTimeImmutable::createFromFormat('Y-m-d H:i:s', "2020-04-01 10:00:00"),
                    "date_retour" => null,
                    "emprunteur" => $emprunteurs[2],
                    "livre" => $livres[2],
                ],
            ];

        foreach ($empruntDatas as $empruntData) {
            $emprunt = new Emprunt();
            $emprunt->setDateEmprunt($empruntData["date_emprunt"]);
            $emprunt->setDateRetour($empruntData["date_retour"]);
            $emprunt->setEmprunteur($empruntData['emprunteur']);
            $emprunt->setLivre($empruntData['livre']);

            $manager->persist($emprunt);
        }

        for ($i = 0; $i < 200; $i++) {
            $emprunt = new Emprunt();
            $date = $faker->dateTimeBetween('-6 month', '+6 month');
            // format : YYYY-mm-dd HH:ii:ss
            $date = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', "2021-{$date->format('m-d H:i:s')}");
            $emprunt->setDateEmprunt($date);
            $emprunt->setDateRetour(null);
            $emprunteur = $faker->randomElements($emprunteurs)[0];
            $emprunt->setEmprunteur($emprunteur);
            $livre = $faker->randomElements($livres)[0];
            $emprunt->setLivre($livre);

            $manager->persist($emprunt);
        }

        $manager->flush();
    }
}
