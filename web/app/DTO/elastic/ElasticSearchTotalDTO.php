<?php

namespace App\DTO\elastic;

class ElasticSearchTotalDTO
{

    public function __construct(public int $value, public string $relation)
    {
        //
    }
}
