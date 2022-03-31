<?php

use App\Http\Controllers\Users\SubscriptionController;
use App\Http\Controllers\Users\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Users\OrderController;
use App\Http\Controllers\Users\FavoritePictureController;

Route::get('/user', function (Request $request) {return auth()->user();}); //Получаем авторизованного пользователя
Route::put('/users', [UserController::class, 'update'])->name('users.update'); //Изменение данных
Route::get('/profile', [UserController::class, 'profile'])->name('profile'); //Переход в профиль
Route::post('/logout', [UserController::class, 'logout'])->name('logout');   //Выход
Route::put('/subscriptions/{id}', [SubscriptionController::class, 'update'])->name('subscriptions.update'); //Остановка подписки
Route::post('/orders/{id}', [OrderController::class, 'store'])->name('orders.store'); //Создание заказа
Route::get('/orders', [OrderController::class, 'index'])->name('orders'); //Вывод заказов
Route::post('/favorite_pictures/{id}', [FavoritePictureController::class, 'store'])->name('favorite_pictures.store'); //Добавление картин в избранное
Route::get('/favorite_pictures', [FavoritePictureController::class, 'index'])->name('favorite_pictures'); //Получение избранных картин пользователя
