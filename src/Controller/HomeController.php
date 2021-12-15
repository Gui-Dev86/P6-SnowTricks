<?php

namespace App\Controller;

use App\Entity\Comments;
use App\Entity\Tricks;
use App\Form\CreateCommentFormType;
use App\Repository\TricksRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

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
    public function trickShow($id, Tricks $tricks, Request $request, EntityManagerInterface $manager, TricksRepository $repositoryTrick): Response
    {
        $comment = new Comments();
        $form = $this->createForm(CreateCommentFormType::class, $comment);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $comment->setDateCreateCom(new \DateTime())
                ->setIsActiveCom(1)
                ->setTricks($tricks)
                ->setUser($this->getUser());
            $manager->persist($comment);
            $manager->flush();
            
            return $this->redirectToRoute('trick', ['id' => $id]);
        }
        
        $trick = $repositoryTrick->findOneById($id);

        return $this->render('trick/trick.html.twig', [
            'trick' => $trick,
            'formCreateComment' => $form->createView(),
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
