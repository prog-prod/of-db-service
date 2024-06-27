<?php

namespace App\Http\Controllers\Api\V1;

use App\Contracts\TelegramServiceInterface;
use App\Http\Controllers\Controller;

class TelegramController extends Controller
{

    public function sendFeedback(TelegramServiceInterface $telegramService) {
        $data = request()->validate([
            'text' => 'required|string'
        ]);

        $telegramService->sendMessageToChannel($data['text']);
        return response(['success', 'Feedback was sent successfully'], 200);
    }
}
