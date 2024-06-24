<?php

namespace App\DTO;

class IncomeDTO
{

    public function __construct(public int $from, public int $to, public ?int $income = null)
    {
    }
}
