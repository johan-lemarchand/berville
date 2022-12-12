<?php

namespace App\Controller;


use App\Entity\Article;
use App\Repository\ArticleRepository;

use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticlesController extends AbstractController
{
    #[Route('/article', name: 'articles')]
    public function index(ArticleRepository $articleRepository, Request $request): Response
    {

        return $this->render('article/index.html.twig', [
            'articles' => $articleRepository->findAllByCreatedAt($request->query->getInt('page', 1))
        ]);
    } // index

    #[Route('article/{slug}', name: 'article_front_show', methods: ['GET'])]
    public function show(Article $article): Response
    {
        return $this->render('article/front_show.html.twig', [
            'articles' => $article,
        ]);
    } // show
} // ArticlesController
