<?php

use App\Http\Controllers\Users\PictureController;
use Illuminate\Support\Facades\Route;

Route::get('/pictures', [PictureController::class, 'index'])->name('pictures.index'); //Все картины
Route::get('/pictures/{id}', [PictureController::class, 'show'])->name('pictures.show'); //Вывод одной картины
