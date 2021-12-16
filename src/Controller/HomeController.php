<?php

namespace App\Controller;

use App\Entity\Comments;
use App\Entity\Tricks;
use App\Form\CreateCommentFormType;
use App\Repository\TricksRepository;
use App\Repository\CommentsRepository;
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
        $tricks = $repository->findAll();
        return $this->render('home/index.html.twig', [
            'tricks' => $tricks,
        ]);
    }

    /**
     * @Route("/trick/{id}", name="trick")
     */
    public function trickShow($id, Tricks $tricks, Request $request, EntityManagerInterface $manager, TricksRepository $repositoryTrick, CommentsRepository $repositoryComment): Response
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
        $comments = $repositoryComment->findByTricks($id, ['dateCreateCom' => "DESC"]);
        
        return $this->render('trick/trick.html.twig', [
            'trick' => $trick,
            'comments' => $comments,
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
