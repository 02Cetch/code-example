<?php

namespace App\Service;

use App\Entity\Article;
use App\Exception\Repository\NotFoundRepositoryException;
use App\Repository\ArticleRepository;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;

class ArticleService
{
    public function __construct(
        private readonly ArticleRepository $articleRepository,
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    public function getArticleBySlug(string $slug): Article
    {
        return $this->articleRepository->findOneBy(['slug' => $slug]);
    }

    public function getLastArticles($limit = 6): array
    {
        return $this->articleRepository->findBy([], ['id' => 'ASC'], $limit);
    }

    /**
     * @param string $tagLink
     * @return Article[]
     */
    public function getArticlesByTagLink(string $tagLink): array
    {
        $queryBuilder = $this->articleRepository->createQueryBuilder('a')
            ->join('a.tags', 't')
            ->where('t.link = :link')
            ->andWhere('a.is_deleted = :is_deleted')

            ->orderBy('a.created_at', 'DESC')

            ->setParameter('link', $tagLink)
            ->setParameter('is_deleted', false);

        $query = $queryBuilder->getQuery();
        return $query->getResult();
    }

    public function getArticlesQueryByTagLink(string $tagLink): AbstractQuery
    {
        $queryBuilder = $this->articleRepository->createQueryBuilder('a')
            ->join('a.tags', 't')
            ->where('t.link = :link')
            ->andWhere('a.is_deleted = :is_deleted')
            ->orderBy('a.created_at', 'DESC')

            ->setParameter('link', $tagLink)
            ->setParameter('is_deleted', false);
        return $queryBuilder->getQuery();
    }

    /**
     * @param string $pattern
     * @return Article[]
     * @throws Exception
     * @throws NotFoundRepositoryException
     */
    public function getArticlesByPattern(string $pattern): array
    {
        $articleIds = $this->getArticleIdsByPattern($pattern);
        return $this->articleRepository->findBy(['id' => $articleIds]);
    }

    /**
     * @throws Exception
     * @throws NotFoundRepositoryException
     */
    public function getArticleIdsByPattern(string $pattern): array
    {
        $sql = "SELECT id FROM article
                WHERE MATCH(title, text, text_short) AGAINST(:pattern IN BOOLEAN MODE)
                AND is_deleted = :is_deleted
                ORDER BY MATCH(title, text, text_short) AGAINST(:pattern IN BOOLEAN MODE) ASC;";

        $connection = $this->entityManager->getConnection();
        $statement = $connection->prepare($sql);

        $result = ($statement->executeQuery(['pattern' => $pattern, 'is_deleted' => false]))->fetchFirstColumn();
        if (!$result) {
            throw new NotFoundRepositoryException("Статьи по вашему запросу не найдены");
        }
        return $result;
    }
}
