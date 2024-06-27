<?php

namespace App\Contracts;

use Telegram\Bot\Api;
use Telegram\Bot\Objects\Update;

interface TelegramServiceInterface
{
    public function setWebhook();

    public function removeWebhook();

    public function getWebhookUrl(): string;

    public function getTelegramBotName(): string;

    public function getWebhookUrlPath(): string;

    public function getWebhookUpdate(): string;

    public function sendMessage(string $string, ?string $chat_id = null);

    public function getChatId();

    public function getTelegramApiSdk(): Api;

    public function getWebhookData(): Update;

    public function sendMessageToChannel(string $string): void;
}
