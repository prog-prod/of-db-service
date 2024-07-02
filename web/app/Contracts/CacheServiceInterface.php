<?php

namespace App\Contracts;

use Closure;

interface CacheServiceInterface
{
    public function getCompressed(array|string $key, mixed $default = null);

    public function setCompressed (string $key, Closure|\DateTimeInterface|\DateInterval|int|null $ttl, Closure $callback);

    public function setCompressedForever(string $key, Closure $callback);
}
