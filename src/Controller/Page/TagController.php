<?php

namespace App\Controller\Page;

use App\Service\ArticleService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TagController extends AbstractController
{
    #[Route('/tag/{tagLink}', name: 'tag_index')]
    public function index(string $tagLink, ArticleService $service): Response
    {
        $articles = $service->getArticlesByTagLink($tagLink);
        return $this->render('pages/tag.html.twig', [
            'page_title' => "RuLeak | $tagLink",
            'articles' => $articles
        ]);
    }
}
