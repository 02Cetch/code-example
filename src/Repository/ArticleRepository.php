<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Article>
 *
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public function save(Article $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Article $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    public function findByTagLink(string $tagLink): array
//    {
//        $queryBuilder = $this->createQueryBuilder('a');
//        $queryBuilder->join('a.tags', 't');
//        $queryBuilder->where('t.link = :link');
//        $queryBuilder->orderBy('a.created_at', 'DESC');
//
//        $queryBuilder->setParameter('link', $tagLink);
//
//        $query = $queryBuilder->getQuery();
//        return $query->getResult();
//    }
//
//    public function getArticlesQueryByTagLink(string $tagLink): AbstractQuery
//    {
//        $queryBuilder = $this->createQueryBuilder('a');
//        $queryBuilder->join('a.tags', 't');
//        $queryBuilder->where('t.link = :link');
//        $queryBuilder->orderBy('a.created_at', 'DESC');
//
//        $queryBuilder->setParameter('link', $tagLink);
//
//        return $queryBuilder->getQuery();
//    }
//
//    /**
//     * @param string $pattern
//     * @return Article[]
//     */
//    public function getArticlesByPattern(string $pattern): array
//    {
//        $queryBuilder = $this->createQueryBuilder('a');
//        $queryBuilder->join('a.cover_image', 'i');
//        $queryBuilder->where("MATCH(a.title, a.text, a.text_short) AGAINST(:pattern IN BOOLEAN MODE)");
//        $queryBuilder->having("MATCH(a.title, a.text, a.text_short) AGAINST(:pattern IN BOOLEAN MODE) > 1");
//
//        $queryBuilder->setParameter('pattern', $pattern);
//
//        $query = $queryBuilder->getQuery();
//        return $query->getResult();
//    }
}
