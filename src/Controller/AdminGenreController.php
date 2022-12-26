<?php

namespace App\Controller;

use App\Entity\Genre;
use App\Form\GenreType;
use App\Repository\GenreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/admin/genre')]
class AdminGenreController extends AbstractController
{
    #[Route('/', name: 'app_admin_genre')]
    public function index(GenreRepository $genreRepository): Response
    {
        return $this->render('admin_genre/index.html.twig', [
            'controller_name' => 'AdminGenreController',
            'genres'=>$genreRepository->findAll()
        ]);
    }
    #[Route('/new',name: 'app_admin_genre_new', methods:['GET','POST'])]
    public function new(Request $request, GenreRepository $genreRepository):Response
    {
        $genre = new Genre();
        $form= $this->createForm(GenreType::class,$genre);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 

            $genreRepository->add($genre,true);

            return $this->redirectToRoute('app_admin_genre', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_genre/new.html.twig',[
            'genre'=>$genre,
            'form'=>$form,
        ]);
    }
    #[Route('/{id}',name:'app_admin_genre_show',methods:['GET'])]
    public function show(Genre $genre):Response
    {

        return $this->render('admin_genre/genre_details.html.twig', [
            'genre'=>$genre
        ]);
    }
    #[Route('/{id}/edit',name:'app_admin_genre_edit',methods:['GET','POST'])]
    public function edit(Genre $genre,Request $request,GenreRepository $genreRepository):Response
    {
        $form= $this->createForm(GenreType::class,$genre);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $genreRepository->add($genre, true);
            return $this->redirectToRoute('app_admin_genre', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin_genre/genre_edit.html.twig', [
            'genre'=>$genre,
            'form'=>$form->createView(),
        ]);
    }
    #[Route('/{id}',name:'app_admin_genre_delete',methods:['POST'])]
    public function delete(GenreRepository $genreRepository, Genre $genre,Request $request):Response
    {        
        if ($this->isCsrfTokenValid('delete'.$genre->getId(), $request->request->get('_token'))) {
        $genreRepository->remove($genre, true);
    }


        return $this->redirectToRoute('app_admin_genre', [], Response::HTTP_SEE_OTHER);
    }

}
