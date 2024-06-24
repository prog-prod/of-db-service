<?php

namespace App\DTO;

class PaginatorMetaDTO
{
        public function __construct(public int $total, public int $per_page, public int $current_page, public ?int $last_page, public ?int $from, public ?int $to)
        {
        }
}
