<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use App\Security\LoginFormAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier, private FlashyNotifier $flashy)
    {
        $this->emailVerifier = $emailVerifier;
    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, LoginFormAuthenticator $authenticator, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user->setRoles(['ROLE_USER']);
            // encode the plain password
            $user->setPassword(
            $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();

            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                ((new TemplatedEmail())
                    ->subject('Confirmation d\'un membre')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
                    ->from('club-judo-berville@test.com')
                    ->to('club-judo-berville@test.com')
                    ->context(['user' => $user])
                ));

            $this->flashy->info('Un email est envoyé à l\'administrateur pour qu\'il valide votre compte');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @throws TransportExceptionInterface
     */
    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, UserRepository $userRepository, MailerInterface $mailer): Response
    {
        $id = $request->get('id'); // récupérer l'identifiant de l'utilisateur à partir de l'url

        // Vérifiez que l'ID utilisateur existe et n'est pas nul
        if (null === $id) {
            return $this->redirectToRoute('app_homepage');
        }

       $user = $userRepository->find($id);

      // Assurez-vous que l'utilisateur existe dans la persistance
       if (null === $user) {
           return $this->redirectToRoute('app_homepage');
       }

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $user);

        } catch (VerifyEmailExceptionInterface $exception) {
           $this->flashy->error('Votre lien de vérification n\est pas bon');
            return $this->redirectToRoute('app_register');
        }

        $this->flashy->success('Vous-avez bien validé votre membre');
        $email = (new TemplatedEmail())
            ->from('club-judo-berville@test.com')
            ->to($user->getEmail())
            ->subject('Validation de compte')
            ->htmlTemplate('registration/member.html.twig')
        ;
        $mailer->send($email);

        return $this->redirectToRoute('app_homepage');
    }
}
