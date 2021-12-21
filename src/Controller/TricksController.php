<?php

namespace App\Controller;

use App\Entity\Tricks;
use App\Entity\Image;
use App\Entity\Video;
use App\Repository\TricksRepository;
use App\Repository\ImageRepository;
use App\Repository\VideoRepository;
use App\Form\CreateTricksFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class TricksController extends AbstractController
{

    /**
     * @Route("/createTrick", name="createTrick")
     */
    public function createTrick(Request $request, EntityManagerInterface $manager, SluggerInterface $slugger): Response
    {
        $trick = new Tricks();

        $form = $this->createForm(CreateTricksFormType::class, $trick);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $dateCreateTrick = new \DateTime();
            $mainImage = $form->get('mainImage')->getData();

            $trick->setDateCreateTrick($dateCreateTrick)
                ->setIsActiveTrick(1)
                ->setUser($this->getUser());

            if($mainImage !== null)
            {
                $originalMainImage = pathinfo($mainImage->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeMainImage = $slugger->slug($originalMainImage);
                $mainImageFilename = 'img/upload/'.$safeMainImage.'-'.uniqid().'.'.$mainImage->guessExtension();
                try {
                    $mainImage->move(
                        $this->getParameter('images_directory'),
                        $mainImageFilename
                    );
                } catch (FileException $e) {
                }

                $trick->setMainImage($mainImageFilename);
            }
            else
            {
                $trick->setMainImage('img/imageDefault.jpg');
            }

            $imagesGallery = $form->get('images')->getData();

            foreach($imagesGallery as $image){
                //generate a new file name
                $fichier = 'img/upload/'.md5(uniqid()) . '.' . $image->guessExtension();

                //copy the file in the file upload
                $image->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );

                //store the images in the database
                $img = new Image();
                $img->setPathImage($fichier);
                $trick->addImage($img);
            }
            $linkVideo = $form->get('videos')->getData();

            $video = new Video();
            $video->setLinkVideo($linkVideo);
            $trick->addVideo($video);

            $manager->persist($trick);
            $manager->flush();
            $this->addFlash('success', 'Votre trick a été ajouté avec succés');
            return $this->redirectToRoute('createTrick');
        }
        return $this->render('trick/createTrick.html.twig', [
            'controller_name' => 'TricksController',
            'formCreateTrick' => $form->createView(),
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
    public function listMedias($id, TricksRepository $repositoryTrick): Response
    {
        $trick = $repositoryTrick->findOneById($id);
        
        return $this->render('trick/mediasTrick.html.twig', [
            'trick' => $trick,
        ]);
    }
}
