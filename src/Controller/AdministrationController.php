<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
    public function pageAdminUsers(): Response
    {
        return $this->render('administration/administrationUsers.html.twig', [
            'controller_name' => 'AdministrationController',
        ]);
    }

    /**
     * @Route("/administrationFigures", name="pageAdminTricks")
     */
    public function pageAdminTricks(): Response
    {
        return $this->render('administration/administrationTricks.html.twig', [
            'controller_name' => 'AdministrationController',
        ]);
    }

    /**
     * @Route("/administrationComments", name="pageAdminComments")
     */
    public function pageAdminComments(): Response
    {
        return $this->render('administration/administrationComments.html.twig', [
            'controller_name' => 'AdministrationController',
        ]);
    }
}
