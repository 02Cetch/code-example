<?php

namespace App\Service;

use App\Entity\Article;
use App\Repository\ArticleRepository;

class ArticleService
{
    public function __construct(private readonly ArticleRepository $articleRepository)
    {
    }

    public function getArticleBySlug(string $slug): Article
    {
        return $this->articleRepository->findOneBy(['slug' => $slug]);
    }

    public function getLastArticles($limit = 6): array
    {
        return $this->articleRepository->findBy([], ['id' => 'ASC'], $limit);
    }

    public function getArticlesByTagLink(string $tagLink): array
    {
        return $this->articleRepository->findByTagLink($tagLink);
    }
}
