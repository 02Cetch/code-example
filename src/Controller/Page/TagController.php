<?php

namespace App\Controller\Page;

use App\Service\ArticleService;
use App\Service\TagService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TagController extends AbstractController
{
    public function __construct(private readonly RequestStack $request)
    {
    }

    #[Route('/tag/{tagLink}', name: 'tag_index')]
    public function index(
        string $tagLink,
        ArticleService $service,
        TagService $tagService,
        PaginatorInterface $paginator
    ): Response {
        $tagTitle = $tagService->getTagByLink($tagLink)->getTitle();
        $articlesQuery = $service->getArticlesQueryByTagLink($tagLink);

        $itemsPerPage = 6;
        $articlePagination = $paginator->paginate(
            $articlesQuery,
            $this->request->getCurrentRequest()->query->getInt('page', 1),
            $itemsPerPage
        );

        return $this->render('pages/tag.html.twig', [
            'page_title' => "RuLeak | $tagTitle",
            'tag_title' => $tagTitle,
            'articles' => $articlePagination
        ]);
    }
}
