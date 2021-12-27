<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\TricksRepository;
use App\Repository\CommentsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class AdministrationController extends AbstractController
{
    /**
     * @Route("/administration", name="pageAdmin")
     */
    public function index(): Response
    {
        return $this->render('administration/administration.html.twig', [
            'controller_name' => 'AdministrationController',
        ]);
    }

    /**
     * @Route("/administrationUsers", name="pageAdminUsers")
     */
    public function pageAdminUsers(UserRepository $userRepository, Request $request): Response
    {
        //users per page
        $limit = 5;
        //recover page
        $page = (int)$request->query->get("page", 1);
        //number total of users by autor
        $total = $userRepository->getTotalUsersAdmin();
        //recover users per page and autor
        $users = $userRepository->getPaginateUsersAdmin($page, $limit);
        //calculate the pages number
        $pagesNumber = ceil($total / $limit);

        return $this->render('administration/administrationUsers.html.twig', [
            'controller_name' => 'AdministrationController',
            'users' => $users,
            'page' => $page,
            'pagesNumber' => $pagesNumber,
        ]);
    }

    /**
     * @Route("/administrationFigures", name="pageAdminTricks")
     */
    public function pageAdminTricks(TricksRepository $tricksRepository, Request $request): Response
    {
        //tricks per page
        $limit = 5;
        //recover page
        $page = (int)$request->query->get("page", 1);
        //number total of tricks by autor
        $total = $tricksRepository->getTotalTricksAdmin();
        //recover tricks per page and autor
        $tricks = $tricksRepository->getPaginateTricksAdmin($page, $limit);
        //calculate the pages number
        $pagesNumber = ceil($total / $limit);
        return $this->render('administration/administrationTricks.html.twig', [
            'controller_name' => 'AdministrationController',
            'tricks' => $tricks,
            'page' => $page,
            'pagesNumber' => $pagesNumber,
        ]);
    }

    /**
     * @Route("/administrationComments", name="pageAdminComments")
     */
    public function pageAdminComments(CommentsRepository $commentsRepository, Request $request): Response
    {
        //comments per page
        $limit = 5;
        //recover page
        $page = (int)$request->query->get("page", 1);
        //number total of comments
        $total = $commentsRepository->getTotalCommentsAdmin();
        //recover comments per page
        $comments = $commentsRepository->getPaginateCommentsAdmin($page, $limit);
        //calculate the pages number
        $pagesNumber = ceil($total / $limit);

        return $this->render('administration/administrationComments.html.twig', [
            'controller_name' => 'AdministrationController',
            'comments' => $comments,
            'page' => $page,
            'pagesNumber' => $pagesNumber,
        ]);
    }
}
