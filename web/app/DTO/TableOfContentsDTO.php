<?php

namespace App\DTO;

class TableOfContentsDTO
{
    public function __construct(public string $href, public string $title)
    {
    }
}
