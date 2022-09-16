<?php

namespace App\Controller\Dashboard;

use App\Entity\{Article, Images, User};
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
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

            $this->addFlash('success', 'Votre tag est bien créé');
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
                $img->setName($avatarFileName['name']);
                $user->addImage($img);
            }
            $entityManager->flush();

            $this->addFlash('success', 'Votre tag est bien edité');
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

        $this->addFlash('success', 'Votre tag est bien supprimé');
        return $this->redirectToRoute('home_user', [], Response::HTTP_SEE_OTHER);
    } // delete

    #[Route('/delete/avatar/{user_id}/{image_id}', name: 'delete_avatar')]
    #[Entity('user', options: ['id'=>'user_id'])]
    #[Entity('image', options: ['id'=>'image_id'])]
    public function deletePhoto(User $user, Images $image, EntityManagerInterface $entityManager): Response
    {
        $user->removeImage($image);
        $entityManager->flush();

        if (Response::HTTP_OK) {
            $this->addFlash('success','Votre photo est bien supprimée');
        } else {
            $this->addFlash('error','Votre photo n\'est pas supprimée, une erreur est survenue');
        }
        return $this->redirectToRoute('user_show', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);
    } // deletePhoto
} // UserController
