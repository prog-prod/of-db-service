<?php

namespace App\Http\Controllers\Admin;

use App\Contracts\TelegramServiceInterface;
use App\Http\Controllers\Controller;

class SettingsController extends Controller
{
    public function setTelegramWebhook(TelegramServiceInterface $telegramService)
    {
        if ($telegramService->setWebhook()) {
            return response(['success', 'Webhook was set successfully'], 200);
        }
        return response(['error', 'Webhook was not set successfully'], 400);
    }

    public function testSendMessageToChannel(TelegramServiceInterface $telegramService) {
        $telegramService->sendMessageToChannel('Test message');
    }
}
