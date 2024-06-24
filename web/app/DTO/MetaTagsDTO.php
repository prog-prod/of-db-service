<?php

namespace App\DTO;

class MetaTagsDTO
{
    public function __construct(public string $title, public string $description, public string $h1)
    {
    }
}
