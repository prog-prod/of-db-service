<?php

namespace App\Repositories;

use App\Contracts\UserRepositoryInterface;
use App\Models\User;

class UserRepository implements UserRepositoryInterface
{

    public function getTotalCountUsers(): int
    {
        return User::query()->count();
    }
}
