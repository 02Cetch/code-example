<?php

namespace App\Service;

use App\Entity\Tag;
use App\Exception\Repository\NotFoundRepositoryException;
use App\Facade\Cache\TagCacheManager;
use App\Repository\TagRepository;

class TagService
{
    public function __construct(
        private readonly TagRepository $tagRepository,
        private readonly TagCacheManager $cacheManager
    ) {
    }

    public function getTopTags(int $limit = 5): array
    {
        return $this->tagRepository->findBy([], ['weight' => 'ASC'], $limit);
    }

    /**
     * @throws NotFoundRepositoryException
     */
    public function getTagsQuantityByUserId(int $userId): array
    {
        $key = "tags_quantity_userid_$userId";
        return $this->cacheManager->cacheByUserId($key, $this->tagRepository->findTagsQuantityByUserId($userId));
    }

    public function getMockTagsCount(): array
    {
        $tags = $this->tagRepository->getMockTagsQuantity();
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
