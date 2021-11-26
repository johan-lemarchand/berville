<?php

namespace App\Controller\Dashboard;

use App\Entity\Role;
use App\Form\RoleType;
use App\Repository\RoleRepository;

use Knp\Component\Pager\PaginatorInterface;

use MercurySeries\FlashyBundle\FlashyNotifier;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\{
    Request,
    Response
};
use Symfony\Component\Routing\Annotation\Route;


#[Route('/admin/role')]
class RoleController extends AbstractController
{
    #[Route('/', name: 'role_home', methods: ['GET'])]
    public function index(RoleRepository $roleRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $data = $roleRepository->findAll();
        $roles = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('role/index.html.twig', [
            'roles' => $roles,
        ]);
    }

    #[Route('/new', name: 'role_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, FlashyNotifier $flashy): Response
    {
        $role = new Role();
        $form = $this->createForm(RoleType::class, $role);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($role);
            $entityManager->flush();

            $flashy->success('Votre rôle est bien créé');
            return $this->redirectToRoute('role_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('role/new.html.twig', [
            'role' => $role,
            'roleForm' => $form,
        ]);
    }

    #[Route('/{id}', name: 'role_show', methods: ['GET'])]
    public function show(Role $role): Response
    {
        return $this->render('role/show.html.twig', [
            'role' => $role,
        ]);
    }

    #[Route('/{id}/edit', name: 'role_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Role $role, EntityManagerInterface $entityManager, FlashyNotifier $flashy): Response
    {
        $form = $this->createForm(RoleType::class, $role);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $flashy->success('Votre rôle est bien edité');
            return $this->redirectToRoute('role_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('role/edit.html.twig', [
            'role' => $role,
            'roleForm' => $form,
        ]);
    }

    #[Route('/{id}', name: 'role_delete', methods: ['DELETE'])]
    public function delete(Request $request, Role $role, EntityManagerInterface $entityManager, FlashyNotifier $flashy): Response
    {
        if ($this->isCsrfTokenValid('delete'.$role->getId(), $request->request->get('_token'))) {
            $entityManager->remove($role);
            $entityManager->flush();
        }

        $flashy->success('Votre rôle est bien supprimé');
        return $this->redirectToRoute('role_home', [], Response::HTTP_SEE_OTHER);
    }
}
