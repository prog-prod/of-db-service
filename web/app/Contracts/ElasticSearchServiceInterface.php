<?php

namespace App\Contracts;

interface ElasticSearchServiceInterface
{
    public function getClient();
    public function setModel(SearchableModel $model): self;
    public function search ($params = [], bool $randomize = false): ?ElasticSearchResponseInterface;
    public function whereIn(string $field, array $values): self;
    public function where(string $field, mixed $value): self;
    public function limit(int $limit): self;
}
