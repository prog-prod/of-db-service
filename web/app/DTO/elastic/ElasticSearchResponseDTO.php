<?php

namespace App\DTO\elastic;

use Elastic\Elasticsearch\Response\Elasticsearch;

class ElasticSearchResponseDTO
{

    public array $hits;
    public ElasticSearchTotalDTO $total;
    public ?int $max_score;

    public function __construct(Elasticsearch $response)
    {
        $response = $response->asArray()['hits'];
        $this->total = new ElasticSearchTotalDTO(
            value: $response['total']['value'],
            relation: $response['total']['relation']
        );
        $this->max_score = $response['max_score'] ?? null;
        $this->hits = array_map(function ($item) {
            return new ElasticSearchHitDTO($item);
        }, $response['hits']);

    }
}
