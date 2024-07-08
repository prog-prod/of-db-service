<?php

use App\Http\Controllers\Api\Parser\ParserController;
use App\Http\Middleware\CheckApiSecretKey;
use Illuminate\Support\Facades\Route;

Route::middleware(CheckApiSecretKey::class)->group(function () {
    Route::post('/onlyfans/update', [ParserController::class, 'updateOfUsers']);
    Route::post('/onlyfans', [ParserController::class, 'addOfUsers']);
    Route::post('/onlyfans/regular', [ParserController::class, 'addRegularOfUsers']);
    Route::get('/search', [ParserController::class, 'search']);
});
