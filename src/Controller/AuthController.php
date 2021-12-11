<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Form\RegisterFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AuthController extends AbstractController
{

    /**
     * @Route("/register", name="register")
     */
    public function register(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder, MailerInterface $mailer): Response
    {
        $user = new User();
        $form = $this->createForm(RegisterFormType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $password = $encoder->encodePassword($user, $user->getPassword());
            $dateCreate = new \DateTime();
            $confirmationToken = md5(random_bytes(60));
            $user->setPassword($password)
                ->setAvatar('img/avatar.png')
                ->setDateCreate($dateCreate)
                ->setDateUpdate($dateCreate)
                ->setIsActive(false)
                ->setRoles(["ROLE_USER"])
                ->setTokenPass($confirmationToken);
            $manager->persist($user);
            $manager->flush();
           
            
            
            //prepare mail to validate the registration
            $message = (new Email())
            ->from('guillaume.vigneres@greta-cfa-aquitaine.academy')
            ->to($user->getEmail())
            ->subject('Confirmation de votre inscription')
            ->html($this->renderView('email/confirmRegister.html.twig', [
                    'username' => $user->getUsername(),
                    'id' => $user->getId(),
                    'token' => $user->getTokenPass(),
                    'address' => $request->server->get('SERVER_NAME')
                ]),
            );
            $mailer->send($message);

            $this->addFlash('success', 'Un mail a été acheminé à l\'adresse indiquée. Afin de terminer votre inscription
            veuillez regarder dans votre boîte mail et suivre les instructions à l\'intérieur du mail.');
            
            return $this->redirectToRoute('register');
        }

        return $this->render('auth/register.html.twig', [
            'controller_name' => 'AuthController',
            'formUser' => $form->createView(),
        ]);
    }
   
    /**
     * Email confirmation
     *
     * @Route(" /mailConfirmation/{id}/{token}", name="mailConfirmation")
     *
     */
    public function mailConfirmation(Request $request, $id, $token, UserRepository $userRepository, EntityManagerInterface $manager): ?Response
    {
        $user = $userRepository->findOneById($id);

        if($token != null && $token === $user->getTokenPass())
        {
            $user->setIsActive(true);
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                "Votre compte a été activé avec succès, désormais vous pouvez vous connecter."
            );
        }
        else
        {
            $this->addFlash(
                'danger',
                "La validation de votre compte a échoué. Le lien de validation a expiré."
            );   
        }
        return $this->redirectToRoute('account_login');
    }

    /**
     * @Route("/login", name="account_login")
     */
    public function login(AuthenticationUtils $AuthenticationUtils): Response
    {
        $error = $AuthenticationUtils->getLastAuthenticationError();
        $username = $AuthenticationUtils->getLastUsername();
        
        return $this->render('security/login.html.twig', [
            'error' => $error,
            '$username' => $username,
        ]);
    }

    /**
     * @Route("/logout", name="account_logout")
     */
    public function logout(): Void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

     /**
     * @Route("/forgotPassword", name="forgotPassword")
     */
    public function forgotPassword(): Response
    {
        return $this->render('auth/forgotPassword.html.twig', [
            'controller_name' => 'AuthController',
        ]);
    }

    /**
     * @Route("/newPassword", name="newPassword")
     */
    public function newPassword(): Response
    {
        return $this->render('auth/newPassword.html.twig', [
            'controller_name' => 'AuthController',
        ]);
    }
}
