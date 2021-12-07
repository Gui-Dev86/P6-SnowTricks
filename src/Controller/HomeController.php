<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Repository\TricksRepository;
use App\Repository\ImageRepository;
use App\Repository\VideoRepository;
use App\Repository\CommentsRepository;
use App\Repository\CategoriesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function index(TricksRepository $repository): Response
    {
        $tricks = $repository->findBy([], ['dateCreateTrick' => 'DESC'],10, 0);
        return $this->render('home/index.html.twig', [
            'tricks' => $tricks,
        ]);
    }

    /**
     * Get the 10 next tricks in the database and create a Twig file with them that will be displayed via Javascript
     * 
     * @Route("/{first}", name="loadMoreTricks", requirements={"first": "\d+"})
     */
    /*public function loadMoreTricks(TricksRepository $repository, $first = 10)
    {
        // Get 10 tricks from the first position
        $tricks = $repository->findBy([], ['dateCreateTrick' => 'DESC'], 10, $first);

        return $this->render('home/loadMoreTricks.html.twig', [
            'tricks' => $tricks,
        ]);
    }*/

    /**
     * @Route("/trick/{id}", name="trick")
     */
    public function trickShow($id, TricksRepository $repositoryTrick, CategoriesRepository $repositoryCategory, ImageRepository $repositoryImage, VideoRepository $repositoryVideo, CommentsRepository $repositoryComment, Request $request): Response
    {
        $trick = $repositoryTrick->findOneById($id);
        
        return $this->render('trick/trick.html.twig', [
            'trick' => $trick,
        ]);
    }

    /**
     * @Route("/mentions-legales", name="legalsMentions")
     */
    public function legalsMentions(): Response
    {
        return $this->render('home/legalsMentions.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
