<?php

namespace App\Contracts;

interface ElasticSearchResponseInterface
{
    public function getTotal(): int;
    public function getHits(): array;
}
