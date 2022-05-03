<?php

use App\Http\Controllers\PHPMailerController;
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
use App\Http\Controllers\Users\EmailController;


//Users
Route::get('/user', function (Request $request) {return new UserResource(auth()->user());}); //Получаем авторизованного пользователя
Route::put('/users', [UserController::class, 'update'])->name('users.update'); //Изменение данных
Route::post('/avatar', [UserController::class, 'avatar'])->name('avatar.update'); //Изменение аватарки
Route::get('/profile', [UserController::class, 'profile'])->name('profile'); //Переход в профиль
Route::post('/logout', [UserController::class, 'logout'])->name('logout');   //Выход
Route::get('/users', [UserController::class, 'index'])->name('users.index'); //Получение всех пользователей



//Orders
Route::post('/orders/{id}', [OrderController::class, 'store'])->name('orders.store'); //Приобретение картины
Route::get('/orders', [OrderController::class, 'index'])->name('orders'); //Вывод заказов

//AccountUser
Route::post('/account_users', [AccountUserController::class, 'store'])->name('account_users.store'); //Приобретение PRO аккаунта

//Favorite
Route::get('/favorite_pictures', [FavoritePictureController::class, 'index'])->name('favorite_pictures'); //Получение избранных картин пользователя
Route::post('/favorite_pictures/{id}', [FavoritePictureController::class, 'store'])->name('favorite_pictures.store'); //Добавление картин в избранное

//Application_users
Route::get('/application_users', [ApplicationUserController::class, 'index'])->name('application_users');


//Subscriptions
Route::post('/subscriptions', [PHPMailerController::class, "user_subscription"])->name('subscriptions.store'); //Подписка на рассылку
Route::put('/subscriptions', [SubscriptionController::class, 'update'])->name('subscriptions.update'); //Остановка и продление подписки

//Verify Email
Route::middleware(['throttle:6,1'])->group(function () {
    Route::post('/email/verification-notification', [EmailController::class, 'repeated_email'])->name('verification.send'); //Получение повторного письма для подтверждения почты
});

Route::middleware(['signed'])->group(function () {
    Route::get('/email/verify/{id}/{hash}', [EmailController::class, 'verify_email'])->name('verification.verify'); //Подтверждение почты
});

