<?php

namespace App\Controller;

use App\Entity\Emprunteur;
use App\Form\EmprunteurType;
use App\Repository\EmprunteurRepository;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/admin/emprunteur')]
class AdminEmprunteurController extends AbstractController
{
    #[Route('/', name: 'app_admin_emprunteur')]
    public function index(EmprunteurRepository $emprunteurRepository): Response
    {
        return $this->render('admin_emprunteur/index.html.twig', [
            'controller_name' => 'AdminEmprunteurController',
            'emprunteurs'=>$emprunteurRepository->findAll()
        ]);
    }
    #[Route('/new', name: 'app_admin_emprunteur_new', methods:['GET','POST'])]
    public function new(EmprunteurRepository $emprunteurRepository, Request $request): Response
    {
        $emprunteur=new Emprunteur();
        $form = $this->createForm(EmprunteurType::class,$emprunteur);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $emprunteur=$emprunteur->setCreatedAt(new DateTimeImmutable());
            $emprunteur=$emprunteur->setUpdatedAt(new DateTimeImmutable());
            $emprunteurRepository->add($emprunteur,true);

            return $this->redirectToRoute('app_admin_emprunteur', [], Response::HTTP_SEE_OTHER);
        };
        return $this->renderForm('admin_emprunteur/new.html.twig', [
            'form'=>$form,
            'emprunteur'=>$emprunteur
        ]);
    }
    #[Route('/{id}',name:'app_admin_emprunteur_show',methods:['GET'])]
    public function show(Emprunteur $emprunteur):Response
    {

        return $this->render('admin_emprunteur/emprunteur_details.html.twig', [
            'emprunteur'=>$emprunteur
        ]);
    }
    #[Route('/{id}/edit',name:'app_admin_emprunteur_edit',methods:['GET','POST'])]
    public function edit(Emprunteur $emprunteur,Request $request,EmprunteurRepository $emprunteurRepository):Response
    {
        $form= $this->createForm(EmprunteurType::class,$emprunteur);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $emprunteur=$emprunteur->setUpdatedAt(new DateTimeImmutable());
            $emprunteurRepository->add($emprunteur, true);
            return $this->redirectToRoute('app_admin_emprunteur', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin_emprunteur/emprunteur_edit.html.twig', [
            'emprunteur'=>$emprunteur,
            'form'=>$form->createView(),
        ]);
    }
    #[Route('/{id}',name:'app_admin_emprunteur_delete',methods:['POST'])]
    public function delete(EmprunteurRepository $emprunteurRepository, Emprunteur $emprunteur,Request $request):Response
    {        
        if ($this->isCsrfTokenValid('delete'.$emprunteur->getId(), $request->request->get('_token'))) {
        $emprunteurRepository->remove($emprunteur, true);
    }


        return $this->redirectToRoute('app_admin_emprunteur', [], Response::HTTP_SEE_OTHER);
    }
}

