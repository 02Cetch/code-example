<?php

namespace App\Service\Cache;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use SymfonyBundles\RedisBundle\Redis\ClientInterface;

class RedisStorageManager
{
    public const TTL_HOUR = 3600;
    public const TTL_DAY = 86400;
    public const TTL_MONTH = 2592000;

    private readonly string $prefix;
    private int $ttl;
    private readonly ClientInterface $redis;

    /**
     * @param string $prefix redis key prefix
     * @param int $ttl time to live in seconds
     * @param ClientInterface $redis redis client
     */
    public function __construct(
        #[Autowire(param: 'app.cache.redis.prefix')]
        string $prefix,
        #[Autowire(self::TTL_HOUR)]
        int $ttl,
        #[Autowire('@SymfonyBundles\RedisBundle\Redis\ClientInterface')]
        ClientInterface $redis,
    ) {
        $this->redis = $redis;
        $this->ttl = $ttl;
        $this->prefix = $prefix;
    }

    public function find(string $key): ?string
    {
        return $this->redis->get($this->getRedisKey($key));
    }

    public function findArrayValue(string $key): ?array
    {
        return $this->redis->hgetall($this->getRedisKey($key));
    }

    public function jsonEncode(array $value): string
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }

    public function jsonDecode(string $value): string
    {
        return json_decode($value, true, flags: JSON_UNESCAPED_UNICODE);
    }

    public function set(string $key, string $value): void
    {
        $key = $this->getRedisKey($key);

        $this->redis->set($key, $value);
        $this->redis->expire($key, $this->ttl);
    }

    public function setArray(string $key, array $value): void
    {
        $key = $this->getRedisKey($key);

        $this->redis->hmset($key, $value);
        $this->redis->expire($key, $this->ttl);
    }

    public function remove(string $key): void
    {
        $this->redis->remove($this->getRedisKey($key));
    }

    public function has(string $key): bool
    {
        $result =  $this->find($this->getRedisKey($key));
        if (!$result) {
            return false;
        }
        return (bool) $result;
    }

    public function getTtl(): int
    {
        return $this->ttl;
    }

    public function setTtl(int $seconds): void
    {
        $this->ttl = $seconds;
    }

    private function getRedisKey(string $key): string
    {
        return sprintf('%s.%s', $this->prefix, $key);
    }
}
