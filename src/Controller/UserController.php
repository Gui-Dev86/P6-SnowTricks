<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\TricksRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class UserController extends AbstractController
{
    /**
     * @Route("/profilUser/{id}", name="profilUser")
     */
    public function profilUser($id, TricksRepository $tricksRepository, UserRepository $userRepository, Request $request): Response
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
            'limit' => $limit,
            'page' => $page,
            'pagesNumber' => $pagesNumber,
        ]);
    }

     /**
     * @Route("/modifyUser/{id}", name="modifyUser")
     */
    public function modifyUser(): Response
    {
        return $this->render('user/modifyUser.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    /**
     * @Route("/modifyPassword/{id}", name="modifyPassword")
     */
    public function modifyPassword(): Response
    {
        return $this->render('user/modifyPassword.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    /**
     * @Route("/listComments/{id}", name="listComments")
     */
    public function listComments(): Response
    {
        return $this->render('user/listComments.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
}
