<?php

namespace App\DTO;

class CategoryDTO
{
    public function __construct(public string $name, public string $key)
    {
    }
}
