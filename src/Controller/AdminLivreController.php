<?php

namespace App\Controller;

use App\Entity\Livre;
use App\Form\LivreType;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\LivreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/book')]
class AdminLivreController extends AbstractController
{
    #[Route('/admin/book', name: 'app_admin_book')]
    public function index(LivreRepository $livreRepository): Response
    {
        return $this->render('admin_book/index.html.twig', [
            'controller_name' => 'AdminLivreController',
            'livres' => $livreRepository->findAll()
        ]);
    }
    #[Route('/new', name: 'app_admin_book_new', methods: ['GET', 'POST'])]
    public function new(Request $request, LivreRepository $livreRepository): Response
    {
        $livre = new Livre();
        $form = $this->createForm(LivreType::class, $livre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $livreRepository->add($livre, true);

            return $this->redirectToRoute('app_admin_book', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_book/new.html.twig', [
            'livre' => $livre,
            'form' => $form,
        ]);
    }
    #[Route('/{id}', name: 'app_admin_book_show', methods: ['GET'])]
    public function show(Livre $livre): Response
    {

        return $this->render('admin_book/book_details.html.twig', [
            'livre' => $livre,
            'auteurs' => $livre->getAuteur(),
        ]);
    }
    #[Route('/{id}/edit', name: 'app_admin_book_edit', methods: ['GET', 'POST'])]
    public function edit(Livre $livre, Request $request, LivreRepository $livreRepository): Response
    {
        $form = $this->createForm(LivreType::class, $livre);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $livreRepository->add($livre, true);
            return $this->redirectToRoute('app_admin_book', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin_book/book_edit.html.twig', [
            'livre' => $livre,
            'form' => $form->createView(),
        ]);
    }
    #[Route('/{id}', name: 'app_admin_book_delete', methods: ['POST'])]
    public function delete(LivreRepository $livreRepository, Livre $livre, Request $request): Response
    {
        if ($this->isCsrfTokenValid('delete' . $livre->getId(), $request->request->get('_token'))) {
            $livreRepository->remove($livre, true);
        }


        return $this->redirectToRoute('app_admin_book', [], Response::HTTP_SEE_OTHER);
    }
}
