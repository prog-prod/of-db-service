<?php

namespace App\Services;

use App\Contracts\CacheServiceInterface;
use Closure;
use DateInterval;
use DateTimeInterface;
use Illuminate\Support\Facades\Cache;

class CacheService implements CacheServiceInterface
{

    public function getCompressed(string|array $key, $default = null)
    {
        $data = Cache::get($key, $default);
        return $data ? unserialize(gzuncompress(base64_decode($data))) : null;
    }

    public function setCompressed (string $key, Closure|DateTimeInterface|DateInterval|int|null $ttl, Closure $callback)
    {
        $data = Cache::remember($key, $ttl, function () use ($callback) {
            $data = $callback();
            return base64_encode(gzcompress(serialize($data), 9));
        });
        return $data ? unserialize(gzuncompress(base64_decode($data))) : null;
    }

    public function setCompressedForever (string $key, Closure $callback)
    {
        $data = Cache::rememberForever($key, function () use ($callback) {
            $data = $callback();
            return base64_encode(gzcompress(serialize($data), 9));
        });

        return $data ? unserialize(gzuncompress(base64_decode($data))) : null;
    }
}
