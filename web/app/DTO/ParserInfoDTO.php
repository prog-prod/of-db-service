<?php

namespace App\DTO;

use Illuminate\Database\Eloquent\Model;

class ParserInfoDTO
{
    public function __construct(public Model $parser)
    {

    }
}
