<?php

namespace App\Service;

use App\Dto\Response\Tags\TagsListItem;
use App\Dto\Response\Tags\TagsListResponse;
use App\Entity\Tag;
use App\Exception\CustomException;
use App\Exception\Repository\NotFoundRepositoryException;
use App\Facade\Cache\TagCacheManager;
use App\Repository\TagRepository;
use EasyCorp\Bundle\EasyAdminBundle\Exception\BaseException;

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
    public function getTagsQuantityByUserId(int $userId): TagsListResponse
    {
        return $this->cacheManager->cacheByUserId($userId, $this->getTagsQuantityFromDbByUserId($userId));
    }

    public function getMockTagsCount(): TagsListResponse
    {
        return new TagsListResponse(array_map(function (array $tag) {
            return (new TagsListItem(
                $tag['title'],
                $tag['quantity']
            ));
        }, $this->tagRepository->getMockTagsQuantity()));
    }

    public function getTagByLink(string $tagLink): Tag
    {
        return $this->tagRepository->findOneBy(['link' => $tagLink]);
    }

    /**
     * @throws NotFoundRepositoryException
     */
    private function getTagsQuantityFromDbByUserId(int $userId): TagsListResponse
    {
        return new TagsListResponse(array_map(function (array $tag) {
            return (new TagsListItem(
                $tag['title'],
                $tag['quantity']
            ));
        }, $this->tagRepository->findTagsQuantityByUserId($userId)));
    }
}
