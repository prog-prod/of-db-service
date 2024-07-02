<?php

namespace App\Facades;

use Closure;
use DateInterval;
use DateTimeInterface;
use Illuminate\Support\Facades\Facade;

/**
 * @method mixed getCompressed(string $key)
 * @method mixed setCompressed(string $key, Closure|DateTimeInterface|DateInterval|int|null $ttl, Closure $callback)
 * @method mixed setCompressedForever(string $key, Closure $callback)
 *
 * @see \App\Services\CacheService
 */
class CacheServiceFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'cacheService';
    }
}
