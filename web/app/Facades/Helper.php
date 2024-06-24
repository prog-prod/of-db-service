<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method bool escapeElasticSearchValue(?string $string)
 * @method string getNameFromKey(string $string)
 *
 * @see \App\Services\HelperService
 */
class Helper extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'helper';
    }
}
