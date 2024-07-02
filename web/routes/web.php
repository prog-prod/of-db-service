<?php

use App\Http\Controllers\OfUsersController;
use App\Http\Controllers\TelegramController;
use Illuminate\Support\Facades\Route;

Route::get('/r', [OfUsersController::class, 'redirectToExternal'])->name('users-of.redirect-to-external');
// Webhooks
Route::post(
    str_replace(
        "<token>",
        config('telegram.bots.onlygirlscom_bot.token'),
        config('telegram.bots.onlygirlscom_bot.webhook_url')
    ),
    TelegramController::class
)->name('telegram.webhook');

