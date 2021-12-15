<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\PasswordUpdate;
use App\Repository\UserRepository;
use App\Repository\TricksRepository;
use App\Repository\CommentsRepository;
use App\Form\UserModifyDatasFormType;
use App\Form\UserModifyPassFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/profilUser/{id}", name="profilUser")
     */
    public function profilUser($id, TricksRepository $tricksRepository, Request $request): Response
    {
        //tricks per page
        $limit = 3;
        //recover page
        $page = (int)$request->query->get("page", 1);
        //number total of tricks by autor
        $total = $tricksRepository->getTotalTricks($id);
        //recover tricks per page and autor
        $tricks = $tricksRepository->getPaginateTricks($page, $limit, $id);
        //calculate the pages number
        $pagesNumber = ceil($total / $limit);

        return $this->render('user/profilUser.html.twig', [
            'controller_name' => 'UserController',
            'tricks' => $tricks,
            'page' => $page,
            'pagesNumber' => $pagesNumber,
        ]);
    }

     /**
     * @Route("/modifyUser/{id}", name="modifyUser")
     */
    public function modifyUser(Request $request, EntityManagerInterface $manager): Response
    {

        $user = $this->getUser();
        $precedentAvatar = $user->getAvatar();
        $form = $this->createForm(UserModifyDatasFormType::class, $user);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid())
        {
            $newAvatar = $form->get('avatar')->getData();
            
            if($newAvatar !== null && $newAvatar !== $precedentAvatar)
            {
               
            }
            else
            {
                $user->setAvatar($precedentAvatar);
            }
            
            $dateUpdate = new \DateTime();
            
            $user->setDateUpdate($dateUpdate);

            $manager->persist($user);
            $manager->flush();

            $this->addFlash('success', 'Les modifications ont bien été prises en compte.');
        }

        return $this->render('user/modifyUser.html.twig', [
            'controller_name' => 'UserController',
            'formModifyUser' => $form->createView(),
        ]);
    }

    /**
     * @Route("/modifyPassword/{id}", name="modifyPassword")
     */
    public function modifyPassword(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder): Response
    {
        $passwordUpdate = new PasswordUpdate();
        $user = $this->getUser();
        
        $form = $this->createForm(UserModifyPassFormType::class, $passwordUpdate);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid())
        {
            $newPassword = $passwordUpdate->getNewPassword();
            $password = $encoder->encodePassword($user, $newPassword);
            $user->setPassword($password);
            $manager->persist($user);
            $manager->flush();

            $this->addFlash('success', 'Votre mot de passe a bien été modifié.');
        }

        return $this->render('user/modifyPassword.html.twig', [
            'controller_name' => 'UserController',
            'formModifyPass' => $form->createView(),
        ]);
    }

    /**
     * @Route("/listComments/{id}", name="listComments")
     */
    public function listComments($id, CommentsRepository $commentsRepository, Request $request): Response
    {
        
        //comments per page
        $limit = 5;
        //recover page
        $page = (int)$request->query->get("page", 1);
        //number total of tricks by autor
        $total = $commentsRepository->getTotalComments($id);
        //recover tricks per page and autor
        $comments = $commentsRepository->getPaginateComments($page, $limit, $id);
        //calculate the pages number
        $pagesNumber = ceil($total / $limit);

        return $this->render('user/listComments.html.twig', [
            'controller_name' => 'UserController',
            'comments' => $comments,
            'page' => $page,
            'pagesNumber' => $pagesNumber,
        ]);
    }
}
