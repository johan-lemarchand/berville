<?php

namespace App\Controller\Dashboard;

use App\Entity\{
    Images,
    User
};
use App\Form\{
    UserEditType,
    UserType
};
use App\Repository\UserRepository;
use App\Service\UploaderHelper;

use Doctrine\ORM\EntityManagerInterface;

use Knp\Component\Pager\PaginatorInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{
    Request,
    Response
};
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/user')]
class UserController extends AbstractController
{
    #[Route('/', name: 'home_user', methods: ['GET'])]
    public function index(UserRepository $userRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $data = $userRepository->findAll();
        $users = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            6
        );

        return $this->render('user/home.html.twig', [
            'users' => $users,
        ]);
    } // index

    #[Route('/new', name: 'user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setRoles(['ROLE_USER']);
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $form->get('password')->getData()
            );
            $user->setPassword(
                $hashedPassword
            );
            $entityManager->persist($user);
            $entityManager->flush();

            //$flashy->success('Votre utilisateur est bien créé');
            return $this->redirectToRoute('home_user', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/new.html.twig', [
            'user' => $user,
            'userForm' => $form,
        ]);
    } // new

    #[Route('/{id}', name: 'user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    } // show

    #[Route('/{id}/edit', name: 'user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager, UploaderHelper $fileUploader): Response
    {
        $form = $this->createForm(UserEditType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $avatar = $form->get('images')->getData();
            if ($avatar) {
                $avatarFileName = $fileUploader->upload($avatar, 'avatar');
                $img = new Images();
                $img->setName($avatarFileName);
                $user->addImage($img);
            }
            $entityManager->flush();

            //$flashy->success('Votre utilisateur est bien edité');
            return $this->redirectToRoute('home_user', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/edit.html.twig', [
            'user' => $user,
            'userForm' => $form,
        ]);
    } // edit

    #[Route('/{id}', name: 'user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager, Images $avatar): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }
        if ($this->isCsrfTokenValid('delete'.$avatar->getId(), $request->request->get('_token'))) {
            $entityManager->remove($avatar);
            $entityManager->flush();
        }

        //$flashy->success('Votre utilisateur est bien supprimé');
        return $this->redirectToRoute('home_user', [], Response::HTTP_SEE_OTHER);
    } // delete
} // UserController
