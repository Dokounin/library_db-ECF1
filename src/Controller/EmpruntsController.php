<?php

namespace App\Controller;

use App\Entity\Emprunt;
use App\Entity\User;
use App\Repository\EmprunteurRepository;
use App\Repository\EmpruntRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;




class EmpruntsController extends AbstractController
{


    #[Route('/emprunts', name: 'user_emprunts')]
    public function index(EmpruntRepository $empruntRepository, EmprunteurRepository $emprunteurRepository): Response
    {
                // les utilisateurs non authentifiés sont renvoyés vers la page de login
                $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

                // création d'une liste d'articles vide
                $emprunts = [];

                if($this->isGranted('ROLE_ADMIN')){
                    $user=$this->getUser();
                    $emprunteur = $emprunteurRepository->findByUser($user);
                    $emprunts=$empruntRepository->findAll();

                }elseif($this->isGranted('ROLE_EMPRUNTEUR')){
                    $user=$this->getUser();
                    $emprunteur = $emprunteurRepository->findByUser($user);
                    $emprunts=$emprunteur->getEmprunts();
                }

        return $this->render('emprunts/index.html.twig', [
            'controller_name' => 'EmpruntsController',
            'emprunts'=>$emprunts,
            'emprunteur'=>$emprunteur,
            
            
            
        ]);
    }


    #[Route('/emprunts/{id}', name: 'emprunts_details')]
    public function empruntsDetails(User $user,Emprunt $emprunt,EmpruntRepository $empruntRepository):Response
    {
        $user=$this->getUser();
        $userId=$user->getId();
        $userEmprunteurId=$emprunt->getEmprunteur()->getUser()->getId();

        $userEmprunteurId=$emprunt->getEmprunteur()->getUser()->getId();
        if($this->isGranted('ROLE_ADMIN')){
            
        }elseif($this->isGranted('ROLE_EMPRUNTEUR')&& $userEmprunteurId!=$userId){
            throw new NotFoundHttpException();

        }
        return $this->render('emprunts/emprunt_details.html.twig', [
            'controller_name' => 'EmpruntsController',
            'emprunt' =>$emprunt
        ]);
    }
}
