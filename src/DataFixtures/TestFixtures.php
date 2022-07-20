<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Emprunteur;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as FakerFactory;
use Faker\Generator as FakerGenerator;

class TestFixtures extends Fixture
{
    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = FakerFactory::create("fr_FR");
        $this->loadUser($manager, $faker);
        $this->loadEmprunteur($manager, $faker);
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
            $user->setPassword($faker->sha256());
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
            $emprunteur->setNom($faker->userName());
            $emprunteur->setPrenom($faker->userName());
            $emprunteur->setTel($faker->numberBetween());
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
}
