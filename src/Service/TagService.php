<?php

namespace App\Service;

use App\Dto\Response\Tag\TagsListItem;
use App\Dto\Response\Tag\TagsListResponse;
use App\Entity\Tag;
use App\Exception\Repository\NotFoundRepositoryException;
use App\Exception\Service\BadInputServiceException;
use App\Exception\Service\NotFoundServiceException;
use App\Mapper\TagsMapper;
use App\Repository\TagRepository;
use App\Service\Cache\TagCacheService;

class TagService
{
    public function __construct(
        private readonly TagRepository $tagRepository,
        private readonly TagCacheService $cacheService
    ) {
    }

    public function getTopTags(int $limit = 5): array
    {
        return $this->tagRepository->findBy([], ['weight' => 'ASC'], $limit);
    }

    /**
     * @throws BadInputServiceException
     * @throws NotFoundServiceException
     */
    public function getTagsQuantityByUserId(int $userId): TagsListResponse
    {
        return $this->cacheService->cacheByUserId($userId, $this->getTagsQuantityFromDbByUserId($userId));
    }

    /**
     * @return TagsListResponse
     * @throws BadInputServiceException
     */
    public function getMockTagsCount(): TagsListResponse
    {
        return new TagsListResponse(array_map(function (array $tag) {
            $dto = new TagsListItem();
            TagsMapper::map($tag, $dto);
            return $dto;
        }, $this->getMockTagsQuantity()));
    }

    public function getTagByLink(string $tagLink): Tag
    {
        return $this->tagRepository->findOneBy(['link' => $tagLink]);
    }

    /**
     * @throws NotFoundServiceException
     */
    public function findTagsQuantityByUserId(int $userId): array
    {
        $queryBuilder = $this->tagRepository->createQueryBuilder('t');
        $queryBuilder->select('t.title', 'COUNT(a.id) as quantity')
            ->join('t.articles', 'a')
            ->join('a.user', 'u')
            ->where('u.id = :userId')
            ->setParameter(':userId', $userId)
            ->groupBy('t.id');
        $query = $queryBuilder->getQuery();

        $tags = $query->getArrayResult();
        if (!$tags) {
            throw new NotFoundServiceException("Теги для пользователя $userId не найдены");
        }
        return $tags;
    }

    public function getMockTagsQuantity(): array
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

    /**
     * @throws BadInputServiceException
     * @throws NotFoundServiceException
     */
    private function getTagsQuantityFromDbByUserId(int $userId): TagsListResponse
    {
        return new TagsListResponse(array_map(function (array $tag) {
            $dto = new TagsListItem();
            TagsMapper::map($tag, $dto);
            return $dto;
        }, $this->findTagsQuantityByUserId($userId)));
    }
}
