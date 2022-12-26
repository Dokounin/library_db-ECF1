<?php

namespace App\Controller;

use App\Entity\Livre;
use App\Form\BookType;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\LivreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/book')]
class AdminLivreController extends AbstractController
{
    #[Route('/admin/book', name: 'app_admin_book')]
    public function index(LivreRepository $bookRepository): Response
    {
        return $this->render('admin_book/index.html.twig', [
            'controller_name' => 'AdminLivreController',
            'books'=>$bookRepository->findAll()
        ]);
    }
    #[Route('/new',name: 'app_admin_book_new', methods:['GET','POST'])]
    public function new(Request $request, LivreRepository $bookRepository):Response
    {
        $book = new Livre();
        $form= $this->createForm(LivreType::class,$book);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 

            $bookRepository->add($book,true);

            return $this->redirectToRoute('app_admin_book', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_book/new.html.twig',[
            'book'=>$book,
            'form'=>$form,
        ]);
    }
    #[Route('/{id}',name:'app_admin_book_show',methods:['GET'])]
    public function show(Livre $book):Response
    {

        return $this->render('admin_book/book_details.html.twig', [
            'book'=>$book,
            'auteurs'=>$book->getAuteur(),
        ]);
    }
    #[Route('/{id}/edit',name:'app_admin_book_edit',methods:['GET','POST'])]
    public function edit(Livre $book,Request $request,LivreRepository $bookRepository):Response
    {
        $form= $this->createForm(BookType::class,$book);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $bookRepository->add($book, true);
            return $this->redirectToRoute('app_admin_book', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin_book/book_edit.html.twig', [
            'book'=>$book,
            'form'=>$form->createView(),
        ]);
    }
    #[Route('/{id}',name:'app_admin_book_delete',methods:['POST'])]
    public function delete(LivreRepository $bookRepository, Livre $book,Request $request):Response
    {        
        if ($this->isCsrfTokenValid('delete'.$book->getId(), $request->request->get('_token'))) {
        $bookRepository->remove($book, true);
    }


        return $this->redirectToRoute('app_admin_book', [], Response::HTTP_SEE_OTHER);
    }
}


