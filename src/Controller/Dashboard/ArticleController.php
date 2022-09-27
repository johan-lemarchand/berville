<?php

namespace App\Controller\Dashboard;

use App\Entity\Article;
use App\Entity\Images;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use App\Service\UploaderHelper;

use Doctrine\ORM\EntityManagerInterface;

use Knp\Component\Pager\PaginatorInterface;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{
    Request,
    Response
};
use Symfony\Component\Routing\Annotation\Route;


#[Route('/admin/article')]
class ArticleController extends AbstractController
{
    #[Route('/', name: 'article', methods: ['GET'])]
    public function index(ArticleRepository $articleRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $data = $articleRepository->findAllByCreatedAt();

        $articles = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('article/home.html.twig', [
            'articles' => $articles,
        ]);
    } // index

    #[Route('/new', name: 'article_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UploaderHelper $fileUploader): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article->setUser($this->getUser());
            $articleImg = $form->get('images')->getData();
            $articleMainImg = $form->get('mainImage')->getData();
            if ($articleImg) {
                foreach ($articleImg as $picture) {
                    $articleFileName = $fileUploader->upload($picture, 'article');
                    if ($articleFileName['error']) {
                        $this->addFlash('error', 'le format de l\'image '.$articleFileName['name'].' n\'est pas correct');
                        return $this->redirectToRoute('article_show', ['id' => $article->getId()], Response::HTTP_SEE_OTHER);
                    } else {
                        $img = new Images();
                        $img->setName($articleFileName['name']);
                        $article->addImage($img);
                        $entityManager->flush();
                        $this->addFlash('success', $articleFileName['name'].' est bien enregistré');
                    }
                }
            }

            if ($articleMainImg) {
                $articleFileName = $fileUploader->upload($articleMainImg, 'mainArticle');
                    if ($articleFileName['error']) {
                        $this->addFlash('error', 'le format de l\'image '.$articleFileName['name'].' n\'est pas correct');
                        return $this->redirectToRoute('article_show', ['id' => $article->getId()], Response::HTTP_SEE_OTHER);
                    } else {
                        $img = new Images();
                        $img->setName($articleFileName['name']);
                        $article->addMainImage($img);
                        $entityManager->flush();
                        $this->addFlash('success', $articleFileName['name'].' est bien enregistré');
                    }
            }
            $entityManager->persist($article);
            $entityManager->flush();

            $this->addFlash('success', 'Votre article est bien créé');
            return $this->redirectToRoute('article', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('article/new.html.twig', [
            'article' => $article,
            'articleForm' => $form,
        ]);
    } // new

    #[Route('/{id}', name: 'article_show', methods: ['GET'])]
    public function show(Article $article): Response
    {
        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    } // show

    #[Route('/{id}/edit', name: 'article_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Article $article, EntityManagerInterface $entityManager, UploaderHelper $fileUploader): Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $articleImg = $form->get('images')->getData();
            $articleMainImg = $form->get('mainImage')->getData();

            if ($articleImg) {
                foreach ($articleImg as $picture) {
                    $articleFileName = $fileUploader->upload($picture, 'article');
                    if ($articleFileName['error']) {
                        $this->addFlash('error', 'le format de l\'image '.$articleFileName['name'].' n\'est pas correct');
                        return $this->redirectToRoute('article_show', ['id' => $article->getId()], Response::HTTP_SEE_OTHER);
                    } else {
                        $img = new Images();
                        $img->setName($articleFileName['name']);
                        $article->addImage($img);
                        $this->addFlash('success', $articleFileName['name'].' est bien enregistré');
                    }
                }
            }

            if ($articleMainImg) {
                $articleFileName = $fileUploader->upload($articleMainImg, 'mainArticle');
                if ($articleFileName['error']) {
                    $this->addFlash('error', 'le format de l\'image '.$articleFileName['name'].' n\'est pas correct');
                    return $this->redirectToRoute('article_show', ['id' => $article->getId()], Response::HTTP_SEE_OTHER);
                } else {
                    $img = new Images();
                    $img->setName($articleFileName['name']);
                    $article->addMainImage($img);
                    $this->addFlash('success', $articleFileName['name'].' est bien enregistré');
                }

            }
            $entityManager->flush();
            return $this->redirectToRoute('article_show', ['id' => $article->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('article/edit.html.twig', [
            'article' => $article,
            'articleForm' => $form,
        ]);
    } // edit

    #[Route('/{id}', name: 'article_delete', methods: ['POST'])]
    public function delete(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $entityManager->remove($article);
            $entityManager->flush();
        }
        $this->addFlash('success','Votre article est bien supprimé');
        return $this->redirectToRoute('article', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/delete/photo/{article_id}/{image_id}', name: 'article_photo_delete')]
    #[Entity('article', options: ['id'=>'article_id'])]
    #[Entity('image', options: ['id'=>'image_id'])]
    public function deletePhoto(Article $article, Images $image, EntityManagerInterface $entityManager): Response
    {
        $article->removeImage($image);
        $entityManager->flush();

        if (Response::HTTP_OK) {
            $this->addFlash('success','Votre photo est bien supprimée');
        } else {
            $this->addFlash('error','Votre photo n\'est pas supprimée, une erreur est survenue');
        }
        return $this->redirectToRoute('article_show', ['id' => $article->getId()], Response::HTTP_SEE_OTHER);
    } // deletePhoto

    #[Route('/delete/main/{article_id}/{image_id}', name: 'article_main_photo_delete')]
    #[Entity('article', options: ['id'=>'article_id'])]
    #[Entity('image', options: ['id'=>'image_id'])]
    public function deleteMainPhoto(Article $article, Images $image, EntityManagerInterface $entityManager): Response
    {
        $article->removeMainImage($image);
        $entityManager->flush();

        if (Response::HTTP_OK) {
            $this->addFlash('success','Votre photo est bien supprimée');
        } else {
            $this->addFlash('error','Votre photo n\'est pas supprimée, une erreur est survenue');
        }

        return $this->redirectToRoute('article_show', ['id' => $article->getId()], Response::HTTP_SEE_OTHER);
    } // deleteMainPhoto
} // ArticleController
