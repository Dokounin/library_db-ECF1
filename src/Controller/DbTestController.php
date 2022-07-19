<?php

namespace App\Controller;

use App\Entity\User;
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


        exit();
    }
}
