<?php

namespace App\DTO\elastic;

class ElasticSearchHitDTO
{

    public mixed $_index;
    public mixed $_type;
    public mixed $_id;
    public mixed $_score;
    public ?array $_ignored;
    public mixed $_source;

    public function __construct(array $item)
    {
        $this->_index = $item['_index'];
        $this->_type = $item['_type'];
        $this->_id = $item['_id'];
        $this->_score = $item['_score'];
        $this->_ignored = $item['_ignored'] ?? null;
        $this->_source = $item['_source'];
    }
}
