<?php

namespace App\DTO;

class ParsersInfoDTO
{
    public function __construct(public int $countActiveParsers, public array $parsers)
    {

    }
}
