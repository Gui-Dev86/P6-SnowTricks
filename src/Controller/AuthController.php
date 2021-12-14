<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Form\RegisterFormType;
use App\Form\ForgotPasswordFormType;
use App\Form\NewPasswordFormType;
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
            'formRegisterUser' => $form->createView(),
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
    public function forgotPassword(Request $request, UserRepository $userRepository, EntityManagerInterface $manager, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ForgotPasswordFormType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $user = $userRepository->findOneByUsername($form->getData('username'));

            if ($user !== null)
            {
                $dateUpdate = new \DateTime();
                $confirmationToken = md5(random_bytes(60));
                $user->setDateUpdate($dateUpdate)
                    ->setTokenPass($confirmationToken);
                $manager->persist($user);
                $manager->flush();
             
                //prepare mail to validate the registration
                $message = (new Email())
                ->from('guillaume.vigneres@greta-cfa-aquitaine.academy')
                ->to($user->getEmail())
                ->subject('Réinitialisation de votre mot de passe')
                ->html($this->renderView('email/newPass.html.twig', [
                        'username' => $user->getUsername(),
                        'id' => $user->getId(),
                        'token' => $user->getTokenPass(),
                        'address' => $request->server->get('SERVER_NAME')
                    ]),
                );
                $mailer->send($message);

                $this->addFlash('success', 'Un email de réinitilisation de votre mot de passe a été envoyé sur l\'email affiliée à votre compte.');
                
                return $this->redirectToRoute('forgotPassword');
            }
            else
            {
                $this->addFlash('error', 'Ce nom d\'utilisateur n\'existe pas.');
                return $this->redirectToRoute('forgotPassword');
            }
        }

        return $this->render('auth/forgotPassword.html.twig', [
            'controller_name' => 'AuthController',
            'formPasswordForgot' => $form->createView(),
        ]);
    }

    /**
     * @Route("/newPassword/{id}/{token}", name="newPassword")
     */
    public function newPassword(Request $request, $id, $token, UserRepository $userRepository, UserPasswordEncoderInterface $encoder, EntityManagerInterface $manager): Response
    {

        $user = $userRepository->findOneById($id);
        $usernameUrl = $user->getUsername();

        $form = $this->createForm(NewPasswordFormType::class, $user);
        $form->handleRequest($request);    

        if($form->isSubmitted() && $form->isValid())
        {
            $formDatas = $form->getData();
            $usernameForm = $formDatas->getUsername();
            
            if ($usernameUrl === $usernameForm)
            {
                if($user->getTokenPass() === $token)
                {
                    $password = $encoder->encodePassword($user, $user->getPassword());
                    $user->setPassword($password);
                    $manager->persist($user);
                    $manager->flush();

                    $this->addFlash(
                        'success',
                        "Votre mot de passe a été modifié avec succès."
                    );
                    return $this->redirectToRoute('account_login');
                }
                else
                {
                    $this->addFlash(
                        'error',
                        "La mofification du mot de passe a échoué."
                    );   
                }
            }
            else
            {
                $this->addFlash(
                    'error',
                    "Le nom d'utilisateur saisi ne correspond pas à celui associé à votre compte."
                );  
            }
        }
        return $this->render('auth/newPassword.html.twig', [
            'controller_name' => 'AuthController',
            'formNewPassword' => $form->createView(),
        ]);
    }
}
