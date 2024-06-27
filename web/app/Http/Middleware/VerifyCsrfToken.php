<?php

namespace App\Http\Middleware;

use App\Contracts\TelegramServiceInterface;
use Illuminate\Contracts\Encryption\Encrypter;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        //
    ];

    public function __construct(Application $app, Encrypter $encrypter, TelegramServiceInterface $telegramService)
    {
        parent::__construct($app, $encrypter);
        $this->except[] = $telegramService->getWebhookUrlPath();
        $this->except[] = route('set-telegram-webhook');
    }
}
