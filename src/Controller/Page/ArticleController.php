<?php

namespace App\Controller\Page;

use App\Service\ArticleService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/article')]
class ArticleController extends AbstractController
{
    #[Route('/read/{slug}', name: 'article_read')]
    public function read(string $slug, ArticleService $articleService): Response
    {
        $article = $articleService->getArticleBySlug($slug);
        return $this->render('pages/article.html.twig', [
            'page_title' => "RuLeak | {$article->getTitle()}",
            'article' => $article
        ]);
    }
}