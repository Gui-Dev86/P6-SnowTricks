<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Tricks;
use App\Entity\Comments;
use App\Entity\Categories;
use App\Entity\Image;
use App\Entity\Video;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('FR-fr');
        $users = [];
        $categories = [];
        $categoriesName = ['Grab', 'Rotation', 'Flip', 'Rotation désaxée', 'Slide', 'One foot trick', 'Old school'];
        $tricksName = ['Mute', 'Indy', '360', '720','Frontflip', 'Backflip', 'Misty', 'Tail slide','Nose slide', 'Method air'];

        // create 10 fake users
        for ($i=0; $i<10; $i++)
        {
            $user = new User();

            if($i == 0)
                    {
                        $user->setUsername('admin');
                    }
                    else
                    {
                        $user->setUsername($faker->userName);
                    }
                 $user->setEmail($faker->safeEmail)
                 ->setPassword($this->passwordHasher->hashPassword($user, 'azerty'))
                 ->setDateCreate($faker->dateTimeBetween('-6 months'))
                 ->setDateUpdate(new \Datetime);
                 if($i == 0)
                 {
                    $user->setRoles(['ROLE_ADMIN']);
                 }
                 else
                 {
                    $user->setRoles(['ROLE_USER']);
                 }
                $user->setIsActive(true)
                    ->setAvatar('img/upload/avatars/avatar'. $faker->numberBetween(1,3) . '.png');
            $manager->persist($user);
            $users[] = $user;
        }
        // create 7 fake categories
        foreach ($categoriesName as $categoryName)
        {
            $category = new Categories();
            $category->setNameCat($categoryName);
            $manager->persist($category);
            $categories[] = $category;
        }
        // create 10 fake tricks
        foreach ($tricksName as $trickName)
        {
            $trick = new Tricks();
            $trick->setTitleTrick($trickName)
                ->setContentTrick($faker->paragraph(5))
                ->setDateCreateTrick(new \Datetime)
                ->setDateUpdateTrick(new \Datetime)
                ->setisActiveTrick(true)
                ->setUser($faker->randomElement($users))
                ->setCategories($faker->randomElement($categories))
                ->setMainImage('img/upload/'. $trick->getTitleTrick(). '_1.jpg');
            $manager->persist($trick);

            // create 4 fake images by trick
            for ($k=1; $k<5; $k++)
            {
                $image = new Image();
                $image->setPathImage('img/upload/'. $trick->getTitleTrick(). '_' . $k . '.jpg')
                      ->setTricks($trick);
                $manager->persist($image);
            }

            // 1 to 2 fake videos by trick
            for ($l=0; $l<mt_rand(1, 2); $l++)
            {
                $video = new Video();
                $video->setlinkVideo('https://www.youtube.com/embed/_Qq-YoXwNQY')
                    ->setTricks($trick);
                $manager->persist($video);
            }

            // 5 to 25 fake comments by trick
            for ($m=0; $m<mt_rand(5, 25); $m++)
            {
                $comment = new Comments();
                $comment->setContentCom($faker->sentence(mt_rand(1, 5)))
                        ->setDateCreateCom(new \Datetime)
                        ->setIsActiveCom(true)
                        ->setUser($faker->randomElement($users))
                        ->setTricks($trick);
                $manager->persist($comment);
            }               
        }
        $manager->flush();
    }
}
