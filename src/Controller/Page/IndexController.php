<?php

namespace App\Controller\Page;

use App\Service\ArticleService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(name: 'app')]
class IndexController extends AbstractController
{
    #[Route('/', name: '_index')]
    public function view(ArticleService $articleService): Response
    {
        $articles = $articleService->getLastArticles();
        return $this->render('pages/index.html.twig', [
            'page_title' => "{$this->getParameter('app.name')} — блог о технологиях и не только",
            'articles' => $articles
        ]);
    }
}
