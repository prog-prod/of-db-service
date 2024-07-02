<?php

namespace App\Contracts;

use App\Models\OfUser;

interface JsonLdServiceInterface
{
    public function generateParams(?OfUser $ofUser, string $key);
}
