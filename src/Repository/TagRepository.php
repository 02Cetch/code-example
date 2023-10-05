<?php

namespace App\Repository;

use App\Entity\Tag;
use App\Exception\Repository\NotFoundRepositoryException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Tag>
 *
 * @method Tag|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tag|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tag[]    findAll()
 * @method Tag[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TagRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tag::class);
    }

    public function save(Tag $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Tag $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @throws NotFoundRepositoryException
     */
    public function findTagsQuantityByUserId(int $userId): array
    {
        $queryBuilder = $this->createQueryBuilder('t');
        $queryBuilder->select('t.title', 'COUNT(a.id) as quantity')
            ->join('t.articles', 'a')
            ->join('a.user', 'u')
            ->where('u.id = :userId')
            ->setParameter(':userId', $userId)
            ->groupBy('t.id');
        $query = $queryBuilder->getQuery();

        $tags = $query->getArrayResult();
        if (!$tags) {
            throw new NotFoundRepositoryException("Теги для пользователя $userId не найдены");
        }
        return $tags;
    }

    public function getMockTagsQuantity(): array
    {
        $queryBuilder = $this->createQueryBuilder('t');
        $queryBuilder->select('t.title', '0 as quantity')
            ->groupBy('t.id');

        $query = $queryBuilder->getQuery();
        $tags = $query->getArrayResult();
        if (!$tags) {
            return [];
        }
        return $tags;
    }

//    /**
//     * @return Tag[] Returns an array of Tag objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Tag
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
