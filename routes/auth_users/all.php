<?php

use App\Http\Controllers\Users\ApplicationUserController;
use App\Http\Controllers\Users\SubscriptionController;
use App\Http\Controllers\Users\UserController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Users\OrderController;
use App\Http\Controllers\Users\FavoritePictureController;
use App\Http\Controllers\Users\AccountUserController;
use App\Http\Resources\UserResource;


//Users
Route::get('/user', function (Request $request) {return new UserResource(auth()->user());}); //Получаем авторизованного пользователя
Route::put('/users', [UserController::class, 'update'])->name('users.update'); //Изменение данных
Route::get('/profile', [UserController::class, 'profile'])->name('profile'); //Переход в профиль
Route::post('/logout', [UserController::class, 'logout'])->name('logout');   //Выход


//Orders
Route::post('/orders/{id}', [OrderController::class, 'store'])->name('orders.store'); //Приобретение картины
Route::get('/orders', [OrderController::class, 'index'])->name('orders'); //Вывод заказов

//AccountUser
Route::post('/account_users', [AccountUserController::class, 'store'])->name('account_users.store'); //Приобретение PRO аккаунта за деньги

//Favorite
Route::get('/favorite_pictures', [FavoritePictureController::class, 'index'])->name('favorite_pictures'); //Получение избранных картин пользователя
Route::post('/favorite_pictures/{id}', [FavoritePictureController::class, 'store'])->name('favorite_pictures.store'); //Добавление картин в избранное

//Application_users
Route::get('/application_users', [ApplicationUserController::class, 'index'])->name('application_users');


//Subscriptions
Route::post('/subscriptions', [SubscriptionController::class, 'store'])->name('subscriptions.store'); //Подписка на рассылку
Route::put('/subscriptions', [SubscriptionController::class, 'update'])->name('subscriptions.update'); //Остановка и продление подписки

//Подтверждение E-mail
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return response()->json('Вы успешно подтвердили свою почту');
})->middleware(['throttle:6,1'])->name('verification.send');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return response()->json('Вы успешно подтвердили свою почту');
})->middleware(['signed'])->name('verification.verify');
