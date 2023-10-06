<?php

namespace App\Dto\Response\Article;

use Knp\Component\Pager\Pagination\PaginationInterface;

class ArticlePaginationResponse
{
    public function __construct(
        private readonly ?string $tagTitle,
        private readonly PaginationInterface $pagination
    ) {
    }

    public function getTagTitle(): string
    {
        return $this->tagTitle;
    }

    public function getPagination(): PaginationInterface
    {
        return $this->pagination;
    }
}
