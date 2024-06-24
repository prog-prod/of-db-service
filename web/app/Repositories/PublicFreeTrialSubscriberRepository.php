<?php

namespace App\Repositories;

use App\Contracts\PublicFreeTrialSubscriberRepositoryInterface;
use App\Models\OfUser;
use App\Models\PublicFreeTrialSubscriber;

class PublicFreeTrialSubscriberRepository implements PublicFreeTrialSubscriberRepositoryInterface
{

    public function addSubscriber(string $email, OfUser $ofUser)
    {
        return PublicFreeTrialSubscriber::query()->create([
            'email' => $email,
            'of_user_id' => $ofUser->id
        ]);
    }
}
