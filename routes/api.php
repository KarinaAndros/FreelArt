<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ApplicationCategoryController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\PictureController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\SubscriptionController;


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







//Для авторизованных пользователей
Route::group(['middleware' => ['auth:sanctum']], function () {


    Route::get('/user', function (Request $request) {return auth()->user();}); //Получаем авторизованного пользователя
    Route::put('/users', [UserController::class, 'update'])->name('users.update'); //Изменение данных
    Route::get('/profile', [UserController::class, 'profile'])->name('profile'); //Переход на профиль
    Route::post('/logout', [UserController::class, 'logout'])->name('logout');   //Выход

    Route::put('/subscriptions/{id}', [SubscriptionController::class, 'update'])->name('subscriptions.update'); //Остановка подписки

    Route::apiResources([
        'genres' => GenreController::class   //Функционал с жанрами
    ]);

    Route::apiResources([
        'application_categories' => ApplicationCategoryController::class   //Функционал с категориями заявок
    ]);


    //Для пользователя с ролью админ
    require_once "admin/user.php";

    //Для пользователя с ролью модератор


    //Для пользователей с ролью executor(исполнители)
    Route::group(['middleware' => ['role:executor']], function () {
        Route::post('/pictures', [PictureController::class, 'store'])->name('pictures.store'); //Добавление картин
        Route::put('/pictures/{id}', [PictureController::class, 'update'])->name('pictures.update'); //Изменение картин
        Route::delete('/pictures/{id}', [PictureController::class, 'destroy'])->name('pictures.destroy'); //Удаление картин
    });


    //Для пользователей с ролью customer(заказчик)
    Route::group(['middleware' => ['role:customer']], function () {
        Route::post('/applications', [ApplicationController::class, 'store'])->name('applications.store'); //Добавление заявок
        Route::put('/applications/{id}', [ApplicationController::class, 'update'])->name('applications.update'); //Изменение заявок
        Route::delete('/applications/{id}', [ApplicationController::class, 'destroy'])->name('applications.destroy'); //Удаление картин
    });

});

//Для всех пользователей

//Пользователи
Route::post('/users', [UserController::class, 'store'])->name('users.store'); //Регистрация
Route::post('/login', [UserController::class, 'login'])->name('login'); //Авторизация
Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show'); //Получение одного пользователя

//Картины
Route::get('/pictures', [PictureController::class, 'index'])->name('pictures.index'); //Все картины
Route::get('/pictures/{id}', [PictureController::class, 'show'])->name('pictures.show'); //Вывод одной картины

//Заявки
Route::get('/applications', [ApplicationController::class, 'index'])->name('applications.index'); //Все заявки
Route::get('/applications/{id}', [ApplicationController::class, 'show'])->name('applications.show'); //Вывод одной заявки

//Подписки
Route::post('/subscriptions', [SubscriptionController::class, 'store'])->name('subscriptions.store'); //Подписка на рассылку






