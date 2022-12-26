<?php

namespace App\Controller;

use App\Entity\Livre;
use App\Form\SearchLivreType;
use App\Repository\AuteurRepository;
use App\Repository\LivreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AcceuilController extends AbstractController
{
    #[Route('/', name: 'acceuil')]
    public function index(LivreRepository $bookRepository, Request $request): Response
    {
        $books=$bookRepository->findAll();

        $form=$this->createForm(SearchLivreType::class);

        $search=$form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $books=$bookRepository->filter($search->get('mot')->getData());
        }

        return $this->render('acceuil/index.html.twig', [
            'controller_name' => 'AcceuilController',
            'books'=>$books,
            'form'=>$form->createView()
        ]);
    }
    #[Route(path: 'livre/{id}', name: 'book_details')]
    public function bookDetail(Livre $book, AuteurRepository $auteurRepository): Response
    {
        
        return $this->render('acceuil/book_details.html.twig',[
            
            'book'=>$book,
            'auteurs'=>$book->getAuteur(),
        ]
            
        );
    }
}

