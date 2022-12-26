<?php

namespace App\Controller;

use App\Entity\Emprunt;
use App\Form\EmpruntType;
use App\Repository\EmpruntRepository;
use DateTimeImmutable;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



#[Route('/admin/emprunt')]
class AdminEmpruntController extends AbstractController
{
    #[Route('/', name: 'app_admin_emprunt')]
    public function index(EmpruntRepository $empruntRepository): Response
    {
        return $this->render('admin_emprunt/index.html.twig', [
            'controller_name' => 'AdminEmpruntController',
            'emprunts'=>$empruntRepository->findAll()
        ]);
    }

    #[Route('/new',name: 'app_admin_emprunt_new', methods:['GET','POST'])]
    public function new(Request $request, EmpruntRepository $empruntRepository):Response
    {
        $emprunt = new Emprunt();
        $form= $this->createForm(EmpruntType::class,$emprunt);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
            $emprunt=$emprunt->setDateEmprunt(new DateTimeImmutable());

            $empruntRepository->add($emprunt,true);

            return $this->redirectToRoute('app_admin_emprunt', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_emprunt/new.html.twig',[
            'emprunt'=>$emprunt,
            'form'=>$form,
        ]);
    }
    #[Route('/{id}',name:'app_admin_emprunt_show',methods:['GET'])]
    public function show(Emprunt $emprunt):Response
    {

        return $this->render('admin_emprunt/emprunt_details.html.twig', [
            'emprunt'=>$emprunt
        ]);
    }
    #[Route('/{id}/edit',name:'app_admin_emprunt_edit',methods:['GET','POST'])]
    public function edit(Emprunt $emprunt,Request $request,EmpruntRepository $empruntRepository):Response
    {
        $form= $this->createForm(EmpruntType::class,$emprunt);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $emprunt->setDateRetour(new DateTimeImmutable());

            $empruntRepository->add($emprunt, true);
            return $this->redirectToRoute('app_admin_emprunt', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin_emprunt/emprunt_edit.html.twig', [
            'emprunt'=>$emprunt,
            'form'=>$form->createView(),
        ]);
    }
    #[Route('/{id}',name:'app_admin_emprunt_retour',methods:['POST'])]
    public function retour(EmpruntRepository $empruntRepository, Emprunt $emprunt,Request $request):Response
    {        
        if ($this->isCsrfTokenValid('retour'.$emprunt->getId(), $request->request->get('_token'))) {
        $emprunt->setDateRetour(new DateTimeImmutable());
        $empruntRepository->add($emprunt, true);
    }


        return $this->redirectToRoute('app_admin_emprunt', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/{id}',name:'app_admin_emprunt_delete',methods:['POST'])]
    public function delete(EmpruntRepository $empruntRepository, Emprunt $emprunt,Request $request):Response
    {        
        if ($this->isCsrfTokenValid('delete'.$emprunt->getId(), $request->request->get('_token'))) {
        $empruntRepository->remove($emprunt, true);
    }


        return $this->redirectToRoute('app_admin_emprunt', [], Response::HTTP_SEE_OTHER);
    }

}
