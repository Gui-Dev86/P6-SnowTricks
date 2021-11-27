<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/profilUser", name="profilUser")
     */
    public function profilUser(): Response
    {
        return $this->render('user/profilUser.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

     /**
     * @Route("/modifyUser", name="modifyUser")
     */
    public function modifyUser(): Response
    {
        return $this->render('user/modifyUser.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    /**
     * @Route("/modifyPassword", name="modifyPassword")
     */
    public function modifyPassword(): Response
    {
        return $this->render('user/modifyPassword.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    /**
     * @Route("/listComments", name="listComments")
     */
    public function listComments(): Response
    {
        return $this->render('user/listComments.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
}
