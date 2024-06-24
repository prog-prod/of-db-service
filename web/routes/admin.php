<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OfUserController;
use App\Http\Controllers\Admin\ParserController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest:admin')->group(function () {
    Route::get('login', [AuthController::class, 'index'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login_process');
});

Route::middleware(['auth_admin:admin'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/count-of-users', [DashboardController::class, 'getTotalOfUsers'])->name('get-total-models');
    Route::get('/count-active-parsers', [DashboardController::class, 'getActiveParsers'])->name('get-active-parsers');

    Route::post('/sync-parser-statuses', [ParserController::class, 'syncParserStatuses'])->name('sync-parser-statuses');
    Route::post('/start-parsing-models', [ParserController::class, 'startParsingModels'])->name('start-parsing-models');
    Route::post('/start-updating-models', [ParserController::class, 'startUpdatingModels'])->name('start-updating-models');
    Route::post('/start-checking-regulars', [ParserController::class, 'startCheckingRegulars'])->name('start-checking-regulars');
    Route::post('/stop-parsing-models', [ParserController::class, 'stopParsingModels'])->name('stop-parsing-models');
    Route::post('/stop-updating-models', [ParserController::class, 'stopUpdatingModels'])->name('stop-updating-models');
    Route::post('/stop-checking-regulars', [ParserController::class, 'stopCheckingRegulars'])->name('stop-checking-regulars');

    Route::group(['controller' => AdminController::class, 'prefix' => 'admins', 'as' => 'admins.'], function () {
        Route::get('/','index')->name('index');
        Route::get('/create','create')->name('create');
    });
    Route::group(['controller' => CategoryController::class, 'prefix' => 'categories', 'as' => 'categories.'], function () {
        Route::get('/','index')->name('index');
        Route::get('/create','create')->name('create');
    });
    Route::group(['controller' => OfUserController::class, 'prefix' => 'of-users', 'as' => 'of-users.'], function () {
        Route::get('/','index')->name('index');
        Route::post('/of_users/update/{ofUser}','update')->name('update');
    });
});
