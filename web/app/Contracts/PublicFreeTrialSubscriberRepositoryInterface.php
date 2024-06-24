<?php

namespace App\Contracts;

use App\Models\OfUser;

interface PublicFreeTrialSubscriberRepositoryInterface
{
    public function addSubscriber(string $email, OfUser $ofUser);
}
