<?php


use App\Http\Controllers\Users\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/users', [UserController::class, 'store'])->name('users.store'); //Регистрация
Route::post('/login', [UserController::class, 'login'])->name('login'); //Авторизация
Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show'); //Получение одного пользователя
Route::get('/users', [UserController::class, 'index'])->name('users.index'); //Получение всех пользователей

