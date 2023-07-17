<?php

namespace App\Controller\Page;

use App\Service\ArticleService;;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(ArticleService $articleService): Response
    {
        $articles = $articleService->getLastArticles();
        return $this->render('pages/index.html.twig', [
            'page_title' => 'RuLeak — блог о технологиях и не только',
            'articles' => $articles
        ]);
    }
}
