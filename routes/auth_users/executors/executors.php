<?php

use App\Http\Controllers\Users\PictureController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Users\FavoriteApplicationController;

Route::group(['middleware' => ['role:executor']], function () {
    Route::post('/pictures', [PictureController::class, 'store'])->name('pictures.store'); //Добавление картин
    Route::put('/pictures/{id}', [PictureController::class, 'update'])->name('pictures.update'); //Изменение картин
    Route::delete('/pictures/{id}', [PictureController::class, 'destroy'])->name('pictures.destroy'); //Удаление картин
    Route::post('/favorite_applications/{id}', [FavoriteApplicationController::class, 'store'])->name('favorite_applications.store'); //Добавление заявок в избранное
    Route::get('/favorite_applications', [FavoriteApplicationController::class, 'index'])->name('favorite_applications'); //Получение избранных заявок пользователя
});
