<?php

namespace App\Facade\Cache;

use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

final class CacheManager
{
    public function __construct(private readonly CacheInterface $cache)
    {
    }

    public function cache(string $key, mixed $value, ?string $tagName = null): mixed
    {
        return $this->cache->get($key, function (ItemInterface $item) use ($value, $tagName) {
            if ($tagName) {
                $item->tag($tagName);
            }
            return $value;
        });
    }

    public function remove(string $key): void
    {
        $this->cache->delete($key);
    }
}
