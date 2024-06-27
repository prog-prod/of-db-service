<?php

namespace App\Rules;

use App\Contracts\OfUserRepositoryInterface;
use App\Models\OfUser;
use Illuminate\Contracts\Validation\Rule;

class OfUserIdExistsRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $ofUserRepository = app(OfUserRepositoryInterface::class);
        $result = $ofUserRepository->searchOfUserById((int)$value);
        // Check if there's at least one result with the specified ID
        return $result->id == $value;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The validation error message.';
    }
}
