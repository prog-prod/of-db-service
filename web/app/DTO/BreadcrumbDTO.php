<?php

namespace App\DTO;

class BreadcrumbDTO
{

    public function __construct(public string $name, public string $url )
    {
    }
}
