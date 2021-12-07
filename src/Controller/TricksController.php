<?php

namespace App\Controller;

use App\Repository\TricksRepository;
use App\Repository\ImageRepository;
use App\Repository\VideoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class TricksController extends AbstractController
{

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


    /**
     * @Route("/listMedias/{id}", name="listMedias")
     */
    public function listMedias($id, TricksRepository $repositoryTrick, ImageRepository $repositoryImage, VideoRepository $repositoryVideo, Request $request): Response
    {
        $trick = $repositoryTrick->findOneById($id);
        
        return $this->render('trick/mediasTrick.html.twig', [
            'trick' => $trick,
        ]);
    }
}
