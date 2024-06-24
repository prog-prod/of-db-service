<?php

use App\Http\Controllers\Api\TagController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

    Route::get('/tags/get-by-key', [TagController::class, 'getTagByKey']);
    Route::get('/tags/get-tags-with-high-traffic', [TagController::class, 'getTagsWithHighTraffic']);
    Route::get('/tags/get-all-tags', [TagController::class, 'getTagsWithHighTraffic']);
    Route::get('/tags/get-random-tags', [TagController::class, 'getRandomTags']);
    Route::get('/tags/get-total-of-tags', [TagController::class, 'getTotalOfTags']);
    Route::get('/tags/get-total-indexed-of-tags', [TagController::class, 'getTotalIndexedOfTags']);
    Route::get('/tags/get-social-network-tags', [TagController::class, 'getSocialNetworkTags']);
    Route::get('/tags/get-country-tags', [TagController::class, 'getCountryTags']);
    Route::get('/tags/get-action-tags', [TagController::class, 'getActionTags']);
    Route::get('/tags/get-popular-tags', [TagController::class, 'getPopularTags']);

