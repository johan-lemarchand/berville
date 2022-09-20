<?php

namespace App\Controller;


use App\Entity\Article;
use App\Repository\ArticleRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticlesController extends AbstractController
{
    #[Route('/article', name: 'articles')]
    public function index(ArticleRepository $articleRepository): Response
    {
        $article = $articleRepository->findAllByCreatedAt();

        return $this->render('article/index.html.twig', [
            'articles' => $article,
        ]);
    } // index

    #[Route('article/{id}', name: 'article_front_show', methods: ['GET'])]
    public function show(Article $article): Response
    {
        return $this->render('article/front_show.html.twig', [
            'articles' => $article,
        ]);
    } // show
} // ArticlesController
