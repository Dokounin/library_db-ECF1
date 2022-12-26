<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/user')]
class AdminUserController extends AbstractController
{
    #[Route('/', name: 'app_admin_user', methods:['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('admin_user/index.html.twig', [
            'controller_name' => 'AdminUserController',
            'users'=>$userRepository->findAll()
        ]);
    }

    #[Route('/new',name: 'app_admin_user_new', methods:['GET','POST'])]
    public function new(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $userPasswordHasher):Response
    {
        $user = new User();
        $form= $this->createForm(UserType::class,$user);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
            $user=$user->setCreatedAt(new DateTimeImmutable());
            $user=$user->setUpdatedAt(new DateTimeImmutable());
            $password=$user->getPassword();
            $hashedPassword = $userPasswordHasher->hashPassword($user, $password);
            $user->setPassword($hashedPassword);
            $userRepository->add($user,true);

            return $this->redirectToRoute('app_admin_user', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_user/new.html.twig',[
            'user'=>$user,
            'form'=>$form,
        ]);
    }
    #[Route('/{id}',name:'app_admin_user_show',methods:['GET'])]
    public function show(User $user):Response
    {

        return $this->render('admin_user/user_details.html.twig', [
            'user'=>$user
        ]);
    }
    #[Route('/{id}/edit',name:'app_admin_user_edit',methods:['GET','POST'])]
    public function edit(User $user,Request $request,UserRepository $userRepository, UserPasswordHasherInterface $userPasswordHasher):Response
    {
        $form= $this->createForm(UserType::class,$user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $user->setUpdatedAt(new DateTimeImmutable());
            $password=$user->getPassword();
            $hashedPassword = $userPasswordHasher->hashPassword($user, $password);
            $user->setPassword($hashedPassword);
            $userRepository->add($user, true);
            return $this->redirectToRoute('app_admin_user', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin_user/user_edit.html.twig', [
            'user'=>$user,
            'form'=>$form->createView(),
        ]);
    }
    #[Route('/{id}',name:'app_admin_user_delete',methods:['POST'])]
    public function delete(UserRepository $userRepository, User $user,Request $request):Response
    {        
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
        $userRepository->remove($user, true);
    }


        return $this->redirectToRoute('app_admin_user', [], Response::HTTP_SEE_OTHER);
    }
}

