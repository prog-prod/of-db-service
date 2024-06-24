<?php

namespace App\Models\Traits;

use App\DTO\PaginatorDTO;
use Closure;

trait ElasticScoutTrait
{
    public static function searchPaginate(string $search = '', ?Closure $callback = null, int $perPage = 50, $page = 1): PaginatorDTO
    {
        $query = static::search($search)->orderBy('priority', 'desc');
        $pageName = 'page';

        if ($callback) {
            $query = $callback($query);
        }
        $query = $query->orderBy('updated_at','desc');

        return new PaginatorDTO($query->paginate($perPage, $pageName , $page));
    }

    public static function simpleSearch(string $search = '', ?Closure $callback = null, int $perPage = 50, $page = 1): PaginatorDTO
    {
        $query = static::search($search);
        $pageName = 'page';
        if ($callback) {
            $query = $callback($query);
        }
        return new PaginatorDTO($query->paginate($perPage, $pageName , $page));
    }
    /**
     * Search and get a collection.
     *
     * @param string $search Search string
     * @param Closure|null $callback Callback function which should expect an instance of Laravel\Scout\Builder
     *
     */
    public static function searchCollection(string $search = '', $searchFunc = null, ?Closure $callback = null)
    {
        $query = static::search($search, $searchFunc);

        if ($callback) {
            $query = $callback($query);
        }

        return $query->paginate(10000);
    }

}
