<?php


use App\Http\Controllers\Users\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\GenreController;
use App\Http\Controllers\Admin\ApplicationCategoryController;

//Users
Route::post('/users', [UserController::class, 'store'])->name('users.store'); //Регистрация
Route::post('/login', [UserController::class, 'login'])->name('login'); //Авторизация
Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show'); //Получение одного пользователя

//Accounts
Route::get('/proAccount', [AccountController::class, 'proAccount'])->name('proAccount'); //Получение информации об уровне PRO

//Genres
Route::get('/genres', [GenreController::class, 'index'])->name('genres.index'); //Получение жанров

//Application categories
Route::get('/application_categories', [ApplicationCategoryController::class, 'index'])->name('application_categories.index'); //Получение категорий заявок
