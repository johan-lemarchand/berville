<?php

namespace App\Controller\Dashboard;

use App\Entity\Role;
use App\Form\RoleType;
use App\Repository\RoleRepository;

use Doctrine\Persistence\ManagerRegistry;

use Knp\Component\Pager\PaginatorInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{
    Request,
    Response
};
use Symfony\Component\Routing\Annotation\Route;


#[Route('/admin/role')]
class RoleController extends AbstractController
{
    /**
     * @param RoleRepository $roleRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
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
    } // index

    /**
     * @param Request $request
     * @param ManagerRegistry $doctrine
     * @return Response
     */
    #[Route('/new', name: 'role_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ManagerRegistry $doctrine): Response
    {
        $role = new Role();
        $form = $this->createForm(RoleType::class, $role);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();
            $entityManager->persist($role);
            $entityManager->flush();

            //$flashy->success('Votre rôle est bien créé');
            return $this->redirectToRoute('role_home');
        }

        return $this->render('role/new.html.twig', [
            'role' => $role,
            'roleForm' => $form->createView(),
        ]);
    } // new

    /**
     * @param Role $role
     * @return Response
     */
    #[Route('/{id}', name: 'role_show', methods: ['GET'])]
    public function show(Role $role): Response
    {
        return $this->render('role/show.html.twig', [
            'role' => $role,
        ]);
    } // show

    /**
     * @param Request $request
     * @param Role $role
     * @param ManagerRegistry $doctrine
     * @return Response
     */
    #[Route('/{id}/edit', name: 'role_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Role $role, ManagerRegistry $doctrine): Response
    {
        $form = $this->createForm(RoleType::class, $role);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $doctrine->getManager()->flush();

            //$flashy->success('Votre rôle est bien edité');
            return $this->redirectToRoute('role_home');
        }

        return $this->render('role/edit.html.twig', [
            'role' => $role,
            'roleForm' => $form->createView(),
        ]);
    } // edit

    /**
     * @param Request $request
     * @param Role $role
     * @param ManagerRegistry $doctrine
     * @return Response
     */
    #[Route('/{id}', name: 'role_delete', methods: ['POST'])]
    public function delete(Request $request, Role $role, ManagerRegistry $doctrine): Response
    {
        if ($this->isCsrfTokenValid('delete'.$role->getId(), $request->request->get('_token'))) {
            $entityManager = $doctrine->getManager();
            $entityManager->remove($role);
            $entityManager->flush();
        }

        //$flashy->success('Votre rôle est bien supprimé');
        return $this->redirectToRoute('role_home');
    } // delete
} // RoleController
