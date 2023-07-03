<?php

namespace App\Service;

use App\Repository\ArticleRepository;

class ArticleService
{
    public function __construct(private readonly ArticleRepository $articleRepository) {}

    public function getLastArticles($limit = 4)
    {
        $queryBuilder = $this->articleRepository->createQueryBuilder('a');
        $queryBuilder->orderBy('a.id')->setMaxResults($limit);

        $query = $queryBuilder->getQuery();
        return $query->getResult();
    }
}
