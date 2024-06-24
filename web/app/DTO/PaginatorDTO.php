<?php

namespace App\DTO;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PaginatorDTO
{
    public PaginatorMetaDTO $meta;
    public array|AnonymousResourceCollection $data;
    private ?LengthAwarePaginator $paginator;

    public function __construct(LengthAwarePaginator $paginator){
        $this->paginator = $paginator;
        $this->meta = new PaginatorMetaDTO(total: $paginator->total(), per_page: $paginator->perPage(), current_page: $paginator->currentPage(), last_page: $paginator->lastPage(), from: $paginator->firstItem(), to: $paginator->lastItem());
        $this->data = $paginator->items();
    }

    public function getCachedVersion(): static
    {
        $this->setPaginator(null);
        $this->setData($this->getData()->toArray(app('request')));
        return $this;
    }
    /**
     * @return LengthAwarePaginator
     */
    public function getPaginator(): LengthAwarePaginator
    {
        return $this->paginator;
    }

    public function first() {
        return collect($this->data)->first()?->resource;
    }

    /**
     * @return array|AnonymousResourceCollection
     */
    public function getData(): array|AnonymousResourceCollection
    {
        return $this->data;
    }

    /**
     */
    public function setPaginator($paginator): void
    {
        $this->paginator = $paginator;
    }

    public function getRandomData(int $number)
    {
        $dataCount = count($this->data);

        if ($number >= $dataCount) {
            return $this->data;
        }

        $randomKeys = array_rand($this->data->toArray(request()), $number);

        if ($number === 1) {
            return [$this->data[$randomKeys]];
        }

        return array_map(function ($key) {
            return $this->data[$key];
        }, $randomKeys);
    }


    /**
     * @param array|AnonymousResourceCollection $data
     */
    public function setData(array|AnonymousResourceCollection $data): void
    {
        $this->data = $data;
    }
}
