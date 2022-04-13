<?php

use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\ApplicationCategoryController;
use App\Http\Controllers\Admin\GenreController;
use App\Http\Controllers\Users\SubscriptionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Users\UserController;

Route::group(['middleware' => ['role:admin']], function () {

    //Accounts
    Route::apiResources([
        'accounts' => AccountController::class
    ]);

    //Genres
    Route::apiResources([
        'genres' => GenreController::class
    ]);

    //Applications_categories
    Route::apiResources([
        'application_categories' => ApplicationCategoryController::class
    ]);

    //Users
    Route::get('/subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions.index'); //Получение подписок из базы
    Route::get('/subscriptions/last', [SubscriptionController::class, 'lastSubscriptions'])->name('subscriptions.last'); //Получение трёх последних подписок

    Route::get('/users/last', [UserController::class, 'lastUsers'])->name('users.last'); //Получение трёх последних зарегистрировавшихся пользователей
    Route::get('/last/accounts', [AccountController::class, 'lastAccounts'])->name('accounts.last'); //Получение трёх последних приобретений PRO аккаунта
});
