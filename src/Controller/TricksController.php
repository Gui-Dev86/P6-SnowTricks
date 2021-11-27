<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TricksController extends AbstractController
{
    /**
     * @Route("/trick", name="trick")
     */
    public function index(): Response
    {
        return $this->render('trick/trick.html.twig', [
            'controller_name' => 'TricksController',
        ]);
    }

    /**
     * @Route("/createTrick", name="createTrick")
     */
    public function createTrick(): Response
    {
        return $this->render('trick/createTrick.html.twig', [
            'controller_name' => 'TricksController',
        ]);
    }

     /**
     * @Route("/modifyTrick", name="modifyTrick")
     */
    public function modifyTrick(): Response
    {
        return $this->render('trick/modifyTrick.html.twig', [
            'controller_name' => 'TricksController',
        ]);
    }
}
