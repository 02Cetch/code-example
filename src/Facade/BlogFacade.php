<?php

namespace App\Facade;

use App\Dto\Response\Article\ArticlePaginationResponse;
use App\Entity\Article;
use App\Exception\Repository\NotFoundRepositoryException;
use App\Helper\HighlightHelper;
use App\Service\ArticleService;
use App\Service\TagService;
use Doctrine\DBAL\Exception;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class BlogFacade
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

    /**
     * @throws Exception
     *
     * @return Article[]
     */
    public function getArticlesBySearchQuery(string $searchQuery): array
    {
        try {
            $articles = $this->service->getArticlesByPattern($this->prepareSearchPattern($searchQuery));

            foreach ($articles as $article) {
                $article->setTitle(HighlightHelper::process($searchQuery, $article->getTitle()));
                $article->setTextShort(HighlightHelper::process($searchQuery, $article->getTextShort()));
            }
        } catch (NotFoundRepositoryException $e) {
            $articles = [];
        }
        return $articles;
    }

    private function prepareSearchPattern(string $searchQuery): string
    {
        $preparedPattern = "";
        $parts = explode(" ", $searchQuery);

        // if pattern longer than one word
        if (count($parts) > 1) {
            foreach ($parts as $part) {
                if (strlen($part) <= 4) {
                    // if pattern has small numeric word
                    if (is_numeric($part)) {
                        $preparedPattern = "+'\"$searchQuery\"'";
                        break;
                    }
                    $part = "+" . strtolower($part);
                }
                $preparedPattern .= "$part ";
            }
        } else {
            $preparedPattern = "*$searchQuery*";
        }

        return $preparedPattern;
    }
}
