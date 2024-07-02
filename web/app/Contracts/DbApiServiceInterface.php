<?php

namespace App\Contracts;

use App\Enums\RequestTypeEnum;

interface DbApiServiceInterface
{
    public function sendRequest(RequestTypeEnum $type, string $route, array $params);
}
