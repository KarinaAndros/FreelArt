<?php

use App\Http\Controllers\Users\PictureController;
use Illuminate\Support\Facades\Route;

Route::get('/pictures', [PictureController::class, 'index'])->name('pictures.index'); //Все картины
Route::get('/pictures/{id}', [PictureController::class, 'show'])->name('pictures.show'); //Вывод одной картины
Route::get('/pictures/filter/{id}/{sort}/{k}/{price}', [PictureController::class, 'getPictures'])->name('pictures.filter'); //Фильтрация картин
Route::get('/main/pictures', [PictureController::class, 'PicturesMain'])->name('pictures.main'); //Получение пяти последних картин
