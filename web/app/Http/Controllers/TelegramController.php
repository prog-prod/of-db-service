<?php

namespace App\Http\Controllers;

use App\Contracts\TelegramServiceInterface;
use Illuminate\Support\Facades\Log;

class TelegramController extends Controller
{
    public function __invoke(TelegramServiceInterface $telegramService)
    {
        $updatesWebhook = $telegramService->getWebhookUpdate();

        Log::info("Received data from telegram: " . $updatesWebhook);
        $apiSdk = $telegramService->getTelegramApiSdk();
        $update = $apiSdk->commandsHandler(true);

        return 'ok';
    }


}
