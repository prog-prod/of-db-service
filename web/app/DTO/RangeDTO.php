<?php

namespace App\DTO;

class RangeDTO
{

    public function __construct(public int $from, public int $to)
    {
    }
}
