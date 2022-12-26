<?php

namespace App\Controller;

use App\Entity\Auteur;
use App\Form\AuteurType;
use App\Repository\AuteurRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
#[Route('/admin/auteur')]
class AdminAuteurController extends AbstractController
{
    #[Route('/', name: 'app_admin_auteur')]
    public function index(AuteurRepository $auteurRepository): Response
    {
        return $this->render('admin_auteur/index.html.twig', [
            'controller_name' => 'AdminAuteurController',
            'auteurs'=>$auteurRepository->findAll()
        ]);
    }
    #[Route('/new',name: 'app_admin_auteur_new', methods:['GET','POST'])]
    public function new(Request $request, AuteurRepository $auteurRepository):Response
    {
        $auteur = new Auteur();
        $form= $this->createForm(AuteurType::class,$auteur);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 

            $auteurRepository->add($auteur,true);

            return $this->redirectToRoute('app_admin_auteur', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_auteur/new.html.twig',[
            'auteur'=>$auteur,
            'form'=>$form,
        ]);
    }
    #[Route('/{id}',name:'app_admin_auteur_show',methods:['GET'])]
    public function show(Auteur $auteur, AuteurRepository $auteurRepository):Response
    {

        return $this->render('admin_auteur/auteur_details.html.twig', [
            'auteur'=>$auteur,

        ]);
    }
    #[Route('/{id}/edit',name:'app_admin_auteur_edit',methods:['GET','POST'])]
    public function edit(Auteur $auteur,Request $request,AuteurRepository $auteurRepository):Response
    {
        $form= $this->createForm(AuteurType::class,$auteur);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $auteurRepository->add($auteur, true);
            return $this->redirectToRoute('app_admin_auteur', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin_auteur/auteur_edit.html.twig', [
            'auteur'=>$auteur,
            'form'=>$form->createView(),
        ]);
    }
    #[Route('/{id}',name:'app_admin_auteur_delete',methods:['POST'])]
    public function delete(AuteurRepository $auteurRepository, Auteur $auteur,Request $request):Response
    {        
        if ($this->isCsrfTokenValid('delete'.$auteur->getId(), $request->request->get('_token'))) {
        $auteurRepository->remove($auteur, true);
    }


        return $this->redirectToRoute('app_admin_auteur', [], Response::HTTP_SEE_OTHER);
    }
}

