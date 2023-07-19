<?php

namespace App\Service;

use App\Entity\Tag;
use App\Exception\ServiceException;
use App\Repository\ArticleRepository;
use App\Repository\TagRepository;

class TagService
{
    public function __construct(
        private readonly TagRepository $tagRepository,
        private readonly ArticleRepository $articleRepository
    ) {}

    public function getTopTags(int $limit = 5): array
    {
        return $this->tagRepository->findBy([], ['weight' => 'ASC'], $limit);
    }

    /**
     * @throws ServiceException
     */
    public function getTagsCountByUserId(int $userId): array
    {
        $queryBuilder = $this->articleRepository->createQueryBuilder('a');
        $queryBuilder->select('t.title', 'COUNT(t.id) as quantity')
            ->join('a.tags', 't')
            ->join('a.user', 'u')
            ->where('u.id = :userId')
            ->setParameter(':userId', $userId)
            ->groupBy('t.id');

        $query = $queryBuilder->getQuery();
        $tags = $query->getArrayResult();
        if (!$tags) {
            throw new ServiceException("Tags for user $userId not found");
        }
        return $tags;
    }

    public function getMockTagsCount(): array
    {
        $queryBuilder = $this->tagRepository->createQueryBuilder('t');
        $queryBuilder->select('t.title', '0 as quantity')
            ->groupBy('t.id');

        $query = $queryBuilder->getQuery();
        $tags = $query->getArrayResult();
        if (!$tags) {
            return [];
        }
        return $tags;
    }

    public function getTagByLink(string $tagLink): Tag
    {
        return $this->tagRepository->findOneBy(['link' => $tagLink]);
    }
}
