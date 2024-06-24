<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 *
 * @see \App\Services\TestPerformanceService
 */
class TestPerformanceFacade extends Facade
{
    /**
     * Get the registered name of the component.
     * @method static void start(string $name)
     * @method static float getExecutionTime(?string $name = null)
     * @method static void writeExecutionTime(?string $name = null)
     * @return string
     *
     * @see \App\Services\TestPerformanceService
     */
    protected static function getFacadeAccessor(): string
    {
        return 'testPerformanceService';
    }
}
