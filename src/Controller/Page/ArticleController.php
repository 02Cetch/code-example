<?php

namespace App\Controller\Page;

use App\Service\ArticleService;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/article')]
class ArticleController extends AbstractController
{
    /**
     * @throws NonUniqueResultException|NoResultException
     */
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
