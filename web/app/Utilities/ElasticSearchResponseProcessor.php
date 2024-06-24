<?php

namespace App\Utilities;

use App\Contracts\ElasticSearchResponseInterface;
use App\DTO\elastic\ElasticSearchHitDTO;
use App\DTO\elastic\ElasticSearchResponseDTO;
use Elastic\Elasticsearch\Response\Elasticsearch;
use Illuminate\Support\Collection;

class ElasticSearchResponseProcessor implements ElasticSearchResponseInterface
{

    private ElasticSearchResponseDTO $response;

    public function __construct(Elasticsearch $response)
    {

        $this->response = new ElasticSearchResponseDTO($response);
    }

    public function getTotal(): int
    {
        return $this->response->total->value;
    }

    /**
     * @return ElasticSearchHitDTO[]
     */
    public function getHits(): array
    {
        return $this->response->hits;
    }

    /**
     * @return Collection
     */
    public function getItems(): Collection
    {
        return collect($this->getHits())->map(fn($item) => $item->_source);
    }
}
