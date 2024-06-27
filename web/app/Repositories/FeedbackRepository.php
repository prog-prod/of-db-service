<?php

namespace App\Repositories;

use App\Contracts\FeedbackRepositoryInterface;
use App\Models\Feedback;

class FeedbackRepository implements FeedbackRepositoryInterface
{
    public function createFeedback(array $validatedData)
    {
        return Feedback::query()->create($validatedData);
    }
}
