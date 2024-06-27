<?php

namespace App\Services;


use App\Contracts\TelegramServiceInterface;
use Telegram\Bot\Api;
use Telegram\Bot\Exceptions\TelegramSDKException;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Objects\Update;

class TelegramService implements TelegramServiceInterface
{
    private Api $telegramApiSdk;
    private string $telegramWebhookDomain;
    private string $telegramBotName;
    private string $channelId;

    public function __construct()
    {
        $this->telegramBotName = $this->getTelegramBotName();
        $this->telegramApiSdk = Telegram::bot($this->telegramBotName);
        $this->channelId = config('telegram.bots.'. $this->telegramBotName. '.channel_id');
        $this->telegramWebhookDomain = config('telegram.bots.' . $this->telegramBotName . '.webhook_domain');
    }

    public function getTelegramBotName(): string
    {
        return collect(array_keys(config('telegram.bots')))->first();
    }

    public function getWebhookUpdate(): string
    {
        return $this->telegramApiSdk->getWebhookUpdate();
    }

    /**
     * @throws TelegramSDKException
     */
    public function setWebhook(): bool
    {
        return $this->telegramApiSdk->setWebhook(
            ['url' => $this->getWebhookUrl()]
        );
    }

    public function getWebhookUrl(): string
    {
        return $this->telegramWebhookDomain . $this->getWebhookUrlPath();
    }

    public function getWebhookUrlPath(): string
    {
        return parse_url(route('telegram.webhook'))['path'];
    }

    /**
     * @throws TelegramSDKException
     */
    public function removeWebhook(): bool
    {
        return $this->telegramApiSdk->removeWebhook();
    }

    /**
     * @param string $string
     * @param string|null $chat_id
     * @throws TelegramSDKException
     */
    public function sendMessage(string $string, ?string $chat_id = null): void
    {
        $chat_id = $chat_id ?? $this->getChatId();
        $this->telegramApiSdk->sendMessage([
            'chat_id' => $chat_id,
            'text' => $string
        ]);
    }

    /**
     * @param string $string
     * @throws TelegramSDKException
     */
    public function sendMessageToChannel(string $string): void
    {
        $this->telegramApiSdk->sendMessage([
            'chat_id' => $this->channelId,
            'text' => $string
        ]);
    }

    /**
     */
    public function getChatId(): int
    {
        $dataObject = $this->telegramApiSdk->getWebhookUpdate();
        return $dataObject->getChat()->id;
    }

    public function getWebhookData(): Update
    {
        return $this->telegramApiSdk->getWebhookUpdate();
    }

    /**
     * @return Api
     */
    public function getTelegramApiSdk(): Api
    {
        return $this->telegramApiSdk;
    }
}
