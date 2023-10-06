<?php

namespace App\Facade;

use App\Dto\Response\Article\ArticlePaginationResponse;
use App\Service\ArticleService;
use App\Service\TagService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class ArticleFacade
{
    private const ARTICLES_PER_PAGE = 6;

    public function __construct(
        private readonly ArticleService $service,
        private readonly TagService $tagService,
        private readonly PaginatorInterface $paginator
    ) {
    }

    public function getPaginatedArticlesByTagLinkAndRequestStack(
        string $tagLink,
        RequestStack $request
    ): ArticlePaginationResponse {
        $articlesQuery = $this->service->getArticlesQueryByTagLink($tagLink);
        $tagTitle = $this->tagService->getTagByLink($tagLink)->getTitle();

        $articlePagination = $this->paginator->paginate(
            $articlesQuery,
            $request->getCurrentRequest()->query->getInt('page', 1),
            self::ARTICLES_PER_PAGE
        );

        return new ArticlePaginationResponse(
            $tagTitle,
            $articlePagination
        );
    }
}
