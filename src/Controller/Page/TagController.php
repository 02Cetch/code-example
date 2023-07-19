<?php

namespace App\Controller\Page;

use App\Service\ArticleService;
use App\Service\TagService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TagController extends AbstractController
{
    #[Route('/tag/{tagLink}', name: 'tag_index')]
    public function index(string $tagLink, ArticleService $service, TagService $tagService): Response
    {
        $tagTitle = $tagService->getTagByLink($tagLink)->getTitle();
        $articles = $service->getArticlesByTagLink($tagLink);
        return $this->render('pages/tag.html.twig', [
            'page_title' => "RuLeak | $tagTitle",
            'tag_title' => $tagTitle,
            'articles' => $articles
        ]);
    }
}
