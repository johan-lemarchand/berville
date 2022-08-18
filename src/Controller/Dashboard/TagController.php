<?php

namespace App\Controller\Dashboard;

use App\Entity\Tag;
use App\Form\TagType;
use App\Repository\TagRepository;

use Doctrine\ORM\EntityManagerInterface;

use Knp\Component\Pager\PaginatorInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{
    Request,
    Response
};
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/tag')]
class TagController extends AbstractController
{
    #[Route('/', name: 'tag_home', methods: ['GET'])]
    public function index(TagRepository $tagRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $data = $tagRepository->findAll();
        $tags = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('tag/index.html.twig', [
            'tags' => $tags,
        ]);
    } // index

    #[Route('/new', name: 'tag_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $tag = new Tag();
        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($tag);
            $entityManager->flush();

            // $flashy->success('Votre tag est bien créé');
            return $this->redirectToRoute('tag_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('tag/new.html.twig', [
            'tag' => $tag,
            'tagForm' => $form,
        ]);
    } // new

    #[Route('/{id}', name: 'tag_show', methods: ['GET'])]
    public function show(Tag $tag): Response
    {
        return $this->render('tag/show.html.twig', [
            'tag' => $tag,
        ]);
    } // show

    #[Route('/{id}/edit', name: 'tag_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Tag $tag, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            // $flashy->success('Votre tag est bien edité');
            return $this->redirectToRoute('tag_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('tag/edit.html.twig', [
            'tag' => $tag,
            'tagForm' => $form,
        ]);
    } // edit

    #[Route('/{id}', name: 'tag_delete', methods: ['POST'])]
    public function delete(Request $request, Tag $tag, EntityManagerInterface $entityManager ): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tag->getId(), $request->request->get('_token'))) {
            $entityManager->remove($tag);
            $entityManager->flush();
        }

        // $flashy->success('Votre tag est bien supprimé');
        return $this->redirectToRoute('tag_home', [], Response::HTTP_SEE_OTHER);
    } // delete
} // TagController
