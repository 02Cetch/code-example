<?php

namespace App\Service;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

class ArticleService
{
    public function __construct(private readonly ArticleRepository $articleRepository) {}

    public function getArticleBySlug(string $slug): Article
    {
        return $this->articleRepository->findOneBy(['slug' => $slug]);
    }

    public function getLastArticles($limit = 4): array
    {
        $queryBuilder = $this->articleRepository->createQueryBuilder('a');
        $queryBuilder->orderBy('a.id')->setMaxResults($limit);

        $query = $queryBuilder->getQuery();
        return $query->getResult();
    }

    public function getArticlesByTagLink(string $tagLink): array
    {
        $queryBuilder = $this->articleRepository->createQueryBuilder('a');
        $queryBuilder->join('a.tags', 't');
        $queryBuilder->where('t.link = :link');
        $queryBuilder->orderBy('a.created_at', 'DESC');

        $queryBuilder->setParameter('link', $tagLink);

        $query = $queryBuilder->getQuery();
        return $query->getResult();
    }
}
