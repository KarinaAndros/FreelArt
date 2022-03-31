<?php

use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\ApplicationCategoryController;
use App\Http\Controllers\Admin\GenreController;
use App\Http\Controllers\Users\SubscriptionController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['role:admin']], function () {
    Route::apiResources([
        'accounts' => AccountController::class
    ]);
    Route::get('/subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions.index'); //Получение списка подписок из базы
    Route::apiResources([
        'genres' => GenreController::class   //Функционал с жанрами
    ]);
    Route::apiResources([
        'application_categories' => ApplicationCategoryController::class   //Функционал с категориями заявок
    ]);
});
