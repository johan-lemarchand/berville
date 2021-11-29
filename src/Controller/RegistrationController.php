<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use App\Security\LoginFormAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
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

    /**
     * @throws NonUniqueResultException
     */
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, LoginFormAuthenticator $authenticator, EntityManagerInterface $entityManager, RoleRepository $roleRepository): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $role = $roleRepository->findOneByRoleString('ROLE_USER');

            $user->setRole($role);
            // encode the plain password
            $user->setPassword(
            $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();

            // generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('club-judo-berville@test.com', 'Club Judo Berville'))
                    ->to($user->getEmail())
                    ->subject('Confirmation de votre adresse email')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );
            $this->flashy->info('Vous avez reçu un email pour confirmer votre compte');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, UserRepository $userRepository): Response
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

        $this->flashy->success('Votre email est bien vérifié');

        return $this->redirectToRoute('app_homepage');
    }
}
