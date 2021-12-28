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
            
            if($linkVideo !== null)
            {
                $video = new Video();
                $video->setLinkVideo($linkVideo);
                $trick->addVideo($video);
            }

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
     * @Route("/modifyTrick/{id}", name="modifyTrick")
     */
    public function modifyTrick($id, Request $request, EntityManagerInterface $manager, SluggerInterface $slugger, Tricks $trick, TricksRepository $repositoryTrick): Response
    {
        //recover the actual main Image
        $precedentMainImg = $trick->getMainImage();
        $form = $this->createForm(CreateTricksFormType::class, $trick);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $newMainImg = $form->get('mainImage')->getData();
            $dateUpdateTrick = new \DateTime();
            $trick->setDateUpdateTrick($dateUpdateTrick);

            if($newMainImg !== null && $newMainImg !== $precedentMainImg)
            {
                $originalMainImage = pathinfo($newMainImg->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeMainImage = $slugger->slug($originalMainImage);
                $mainImageFilename = 'img/upload/'.$safeMainImage.'-'.uniqid().'.'.$newMainImg->guessExtension();
                try {
                    $newMainImg->move(
                        $this->getParameter('images_directory'),
                        $mainImageFilename
                    );
                } catch (FileException $e) {
                }

                $trick->setMainImage($mainImageFilename);
            }
            else
            {
                $trick->setMainImage($precedentMainImg);
            }
            //add new images
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
            //add new video
            if($linkVideo !== null)
            {
                $video = new Video();
                $video->setLinkVideo($linkVideo);
                $trick->addVideo($video);
            }
            $manager->persist($trick);
            $manager->flush();
            $this->addFlash('success', 'Votre trick a été modifié avec succés');
            return $this->redirectToRoute('modifyTrick', ['id' => $id]);

        }

        return $this->render('trick/modifyTrick.html.twig', [
            'controller_name' => 'TricksController',
            'formModifyTrick' => $form->createView(),
            'trickId' => $id,
        ]);
    }

    /**
     * @Route("/listDeleteImages/{id}", name="listDeleteImages")
     */
    public function listDeleteImages(Tricks $trick): Response
    {      
        return $this->render('trick/listDeleteImages.html.twig', [
            'trick' => $trick,
        ]);
    }

    /**
     * @Route("/listDeleteVideos/{id}", name="listDeleteVideos")
     */
    public function listDeleteVideos(Tricks $trick): Response
    {      
        return $this->render('trick/listDeleteVideos.html.twig', [
            'trick' => $trick,
        ]);
    }

    /**
     * @Route("/deleteImage/{id}", name="deleteImage")
     */
    public function deleteImage($id, ImageRepository $repositoryImage, Request $request): Response
    {
        $imageDel = (int)$request->query->get("image");
        $datasImage = $repositoryImage->findOneById($imageDel);
        $pathImage=$datasImage->getPathImage();
        
        unlink($this->getParameter('delImages_directory').'/'.$pathImage);
        
        $manager = $this->getDoctrine()->getManager();
            $manager->remove($datasImage);
            $manager->flush();

        $this->addFlash('success', 'L\'image a été supprimé avec succés');

        return $this->redirectToRoute('listDeleteImages', ['id' => $id]);
    }

    /**
     * @Route("/deleteVideo/{id}", name="deleteVideo")
     */
    public function deleteVideo($id, VideoRepository $repositoryVideo, Request $request): Response
    {

        $videoDel = (int)$request->query->get("video");
        $datasVideo = $repositoryVideo->findOneById($videoDel);
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($datasVideo);
        $manager->flush();
        
        $this->addFlash('success', 'La vidéo a été supprimé avec succés');
        
        return $this->redirectToRoute('listDeleteVideos', ['id' => $id]);
    }

    /**
     * @Route("/deleteTrick/{id}", name="deleteTrick")
     */
    public function deleteTrick($id, Tricks $trick, Request $request, TricksRepository $tricksRepository): Response
    {
        $user = $this->getUser();
        $idUser = $user->getId();
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($trick);
        $manager->flush();
        
        $admin = (int)$request->query->get("admin");
        
        if( $admin == 1) {
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
            
            return $this->redirectToRoute('pageAdminTricks', [
                'tricks' => $tricks,
                'page' => $page,
                'pagesNumber' => $pagesNumber,
            ]);
        }
        else
        {
            $this->addFlash('success', 'Le trick a été supprimé avec succés');
            return $this->redirectToRoute('profilUser', ['id' => $idUser]);
        }
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
