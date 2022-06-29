<?php

namespace App\Controller\Dashboard;

use App\Entity\Role;
use App\Form\RoleType;
use App\Repository\RoleRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/role")
 */
class RoleController extends AbstractController
{
    /**
     * @Route("/", name="role_home", methods={"GET"})
     * @param RoleRepository $roleRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
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

    /**
     * @Route("/new", name="role_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $role = new Role();
        $form = $this->createForm(RoleType::class, $role);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($role);
            $entityManager->flush();

            //$flashy->success('Votre rôle est bien créé');
            return $this->redirectToRoute('role_home');
        }

        return $this->render('role/new.html.twig', [
            'role' => $role,
            'roleForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="role_show", methods={"GET"})
     * @param Role $role
     * @return Response
     */
    public function show(Role $role): Response
    {
        return $this->render('role/show.html.twig', [
            'role' => $role,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="role_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Role $role
     * @return Response
     */
    public function edit(Request $request, Role $role): Response
    {
        $form = $this->createForm(RoleType::class, $role);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            //$flashy->success('Votre rôle est bien edité');
            return $this->redirectToRoute('role_home');
        }

        return $this->render('role/edit.html.twig', [
            'role' => $role,
            'roleForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="role_delete", methods={"POST"})
     * @param Request $request
     * @param Role $role
     * @return Response
     */
    public function delete(Request $request, Role $role): Response
    {
        if ($this->isCsrfTokenValid('delete'.$role->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($role);
            $entityManager->flush();
        }

        //$flashy->success('Votre rôle est bien supprimé');
        return $this->redirectToRoute('role_home');
    }
}
