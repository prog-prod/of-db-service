<?php

use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Api\V1\TelegramController;
use App\Http\Controllers\Api\V1\FeedbackController;
use App\Http\Controllers\Api\V1\NginxController;
use App\Http\Controllers\Api\V1\OfTagGroupController;
use App\Http\Controllers\Api\V1\OfUserController;
use App\Http\Controllers\Api\V1\TagController;
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
Route::get('/tags/get-all-tags', [TagController::class, 'getAllTags']);
Route::get('/tags/get-random-tags', [TagController::class, 'getRandomTags']);
Route::get('/tags/get-total-of-tags', [TagController::class, 'getTotalOfTags']);
Route::get('/tags/get-total-indexed-of-tags', [TagController::class, 'getTotalIndexedOfTags']);
Route::get('/tags/get-social-network-tags', [TagController::class, 'getSocialNetworkTags']);
Route::get('/tags/get-country-tags', [TagController::class, 'getCountryTags']);
Route::get('/tags/get-action-tags', [TagController::class, 'getActionTags']);
Route::get('/tags/get-popular-tags', [TagController::class, 'getPopularTags']);
Route::get('/tags/get-of-tag-users', [TagController::class, 'getOfTagUsers']);


Route::get('/of-users/search-top-of-users', [OfUserController::class, 'searchTopOfUsers']);
Route::get('/of-users/search-of-users-for-ads', [OfUserController::class, 'searchOfUsersForAds']);
Route::get('/of-users/search-free-of-users', [OfUserController::class, 'searchFreeOfUsers']);
Route::get('/of-users/search-free-of-users-order-by-likes', [OfUserController::class, 'searchFreeOfUsersOrderByLikes']);
Route::get('/of-users/get-total-of-users', [OfUserController::class, 'getTotalOfUsers']);
Route::get('/of-users/search-newest-of-users', [OfUserController::class, 'searchNewestOfUsers']);
Route::get('/of-users/search-of-users-by-tag', [OfUserController::class, 'searchOfUsersByTag']);
Route::get('/of-users/search-of-users', [OfUserController::class, 'searchOfUsers']);
Route::get('/of-users/get-total-indexed-of-users', [OfUserController::class, 'getTotalIndexedOfUsers']);
Route::get('/of-users/get-of-user-by-username', [OfUserController::class, 'getOfUserByUsername']);
Route::get('/of-users/get-of-users-for-indexation', [OfUserController::class, 'getOfUsersForIndexation']);
Route::get('/of-users/search-of-user-by-id', [OfUserController::class, 'searchOfUserById']);
Route::get('/of-users/get-main-last-modified-date', [OfUserController::class, 'getMainLastModifiedDate']);
Route::get('/of-users/get-similar-of-users', [OfUserController::class, 'getSimilarOfUsers']);
Route::get('/of-users/get-category-of-users', [OfUserController::class, 'getCategoryOfUsers']);
Route::get('/of-users/get-random-of-user-tag', [OfUserController::class, 'getRandomOfUserTag']);
Route::get('/of-users/get-tags', [OfUserController::class, 'getTags']);

Route::get('/tags-group/get-of-tags-groups-with-limited-tags', [OfTagGroupController::class, 'getOfTagsGroupsWithLimitedTags']);
Route::get('/tags-group/get-of-tags-locations-groups-with-limited-tags', [OfTagGroupController::class, 'getOfTagsLocationsGroupsWithLimitedTags']);
Route::get('/tags-group/get-of-tags-group-by-key-with-tags', [OfTagGroupController::class, 'getOfTagsGroupByKeyWithTags']);

Route::get('/feedback/create-feedback', [FeedbackController::class, 'createFeedback']);


Route::post('/nginx-request-frequency/write-request-frequency', [NginxController::class, 'writeRequestFrequency']);
Route::get('/nginx-request-frequency/get-nginx-request-frequency-for-the-period', [NginxController::class, 'getNginxRequestFrequencyForThePeriod']);


Route::post('/set-telegram-webhook-onlygirlscom_bot', [SettingsController::class, 'setTelegramWebhook'])->name(
    'set-telegram-webhook'
);

Route::post('telegram/send-feedback', [TelegramController::class, 'sendFeedback'])->name(
    'telegram.send-feedback'
);
