<?php

namespace App\Contracts;

interface FeedbackRepositoryInterface
{

    public function createFeedback(array $validatedData);
}
