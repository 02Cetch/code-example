<?php

namespace App\Service;

use App\Dto\Response\Tag\TagsListItem;
use App\Dto\Response\Tag\TagsListResponse;
use App\Entity\Tag;
use App\Exception\Repository\NotFoundRepositoryException;
use App\Facade\Cache\TagCacheFacade;
use App\Repository\TagRepository;

class TagService
{
    public function __construct(
        private readonly TagRepository $tagRepository,
        private readonly TagCacheFacade $cacheFacade
    ) {
    }

    public function getTopTags(int $limit = 5): array
    {
        return $this->tagRepository->findBy([], ['weight' => 'ASC'], $limit);
    }

    public function getArticlesByTagLink(string $tagLink)
    {
        $tagTitle = $this->getTagByLink($tagLink)->getTitle();
        $articlesQuery = $service->getArticlesQueryByTagLink($tagLink);

        $itemsPerPage = 6;
        $articlePagination = $paginator->paginate(
            $articlesQuery,
            $this->request->getCurrentRequest()->query->getInt('page', 1),
            $itemsPerPage
        );
    }

    /**
     * @throws NotFoundRepositoryException
     */
    public function getTagsQuantityByUserId(int $userId): TagsListResponse
    {
        return $this->cacheFacade->cacheByUserId($userId, $this->getTagsQuantityFromDbByUserId($userId));
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
