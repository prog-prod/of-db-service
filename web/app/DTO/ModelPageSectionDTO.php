<?php

namespace App\DTO;

class ModelPageSectionDTO
{
    public function __construct(public string $h2, public ?string $text)
    {
    }
}
