<?php

namespace App\Facade\Cache;

final class TagCacheManager
{
    private const KEY = "tags_quantity_userid";

    public function __construct(private readonly CacheManager $manager)
    {
    }

    public function cacheByUserId(int $userId, mixed $value): mixed
    {
        return $this->manager->cache(self::KEY . "_$userId", $value);
    }

    public function resetByUserId(int $userId): void
    {
        $this->manager->remove(self::KEY . "_$userId");
    }
}
