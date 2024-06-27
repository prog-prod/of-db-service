<?php

namespace App\Rules;

use App\Exceptions\InvalidRedirectURLException;
use Illuminate\Contracts\Validation\Rule;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RedirectAllowedDomainsRule implements Rule
{
    protected $whitelistPattern;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $whitelist = config('app.redirect_allowed_domains');
        $this->whitelistPattern = '/^(' . implode('|', array_map(function ($domain) {
                return '(.+\.)?' . preg_quote($domain);
            }, $whitelist)) . ')$/i';
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     * @throws InvalidRedirectURLException
     */
    public function passes($attribute, $value): bool
    {
        if (!filter_var($value, FILTER_VALIDATE_URL)) {
            throw new NotFoundHttpException();
        }

        $host = strtolower(parse_url($value, PHP_URL_HOST));

        if (preg_match($this->whitelistPattern, $host)) {
            return true;
        }

        throw new NotFoundHttpException();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The provided URL is not allowed.';
    }
}
