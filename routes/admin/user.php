<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SubscriptionController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['role:admin']], function () {
    Route::get('/genres', [PageController::class, 'genres'])->name('genres.index'); //Получение списка жанров из базы
    Route::apiResources([
        'accounts' => AccountController::class
    ]);
    Route::get('/subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions.index'); //Получение списка подписок из базы
});
