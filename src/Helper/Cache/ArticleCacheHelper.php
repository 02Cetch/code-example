<?php

namespace App\Helper\Cache;

use App\Entity\Article;
use App\Service\Cache\RedisStorageManager;

class ArticleCacheHelper
{
    private Article $article;

    public function __construct(private readonly RedisStorageManager $cache)
    {
    }

    public function reset(Article $article): void
    {
        $this->article = $article;
        $this->resetTagsQuantity();
    }

    private function resetTagsQuantity(): void
    {
        $this->cache->remove("tags:quantity:userid:{$this->article->getUser()->getId()}");
    }
}
