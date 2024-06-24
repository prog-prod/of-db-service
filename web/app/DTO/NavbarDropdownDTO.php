<?php

namespace App\DTO;

use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class NavbarDropdownDTO
{

    public function __construct(public string $name, public AnonymousResourceCollection $categories)
    {

    }
}
