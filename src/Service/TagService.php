<?php

namespace App\Service;

use App\Entity\Tag;
use App\Exception\NotFoundRepositoryException;
use App\Repository\TagRepository;
use App\Service\Cache\RedisStorageManager;

class TagService
{
    public function __construct(
        private readonly TagRepository $tagRepository,
        private readonly RedisStorageManager $cache
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
        $key = "tags:quantity:userid:$userId";
        if ($this->cache->has($key)) {
            $tags = $this->cache->jsonDecode($this->cache->find($key));
        } else {
            $tags = $this->tagRepository->findTagsQuantityByUserId($userId);
            $this->cache->set($key, $this->cache->jsonEncode($tags));
        }
        return $tags;
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
