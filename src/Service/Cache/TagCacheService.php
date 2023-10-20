<?php

namespace App\Service\Cache;

final class TagCacheService
{
    private const KEY = "tags_quantity_userid";

    public function __construct(private readonly CacheService $service)
    {
    }

    public function cacheByUserId(int $userId, mixed $value): mixed
    {
        return $this->service->cache(self::KEY . "_$userId", $value);
    }

    public function resetByUserId(int $userId): void
    {
        $this->service->remove(self::KEY . "_$userId");
    }
}
