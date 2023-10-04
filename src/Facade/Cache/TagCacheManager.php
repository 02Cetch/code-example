<?php

namespace App\Facade\Cache;

final class TagCacheManager
{
    public function __construct(private readonly CacheManager $manager)
    {
    }

    public function cacheByUserId(int $userId, mixed $value): mixed
    {
        return $this->manager->cache("tags_quantity_userid_$userId", $value);
    }

    public function resetByUserId(int $userId): void
    {
        $this->manager->remove("tags_quantity_userid_$userId");
    }
}
