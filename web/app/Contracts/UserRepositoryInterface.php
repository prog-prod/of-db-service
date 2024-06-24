<?php

namespace App\Contracts;

use App\Models\User;

interface UserRepositoryInterface
{
    public function getTotalCountUsers();
}
