<?php

use App\Http\Controllers\Users\PictureController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Users\ModeratorController;

Route::group(['middleware' => ['role:moderator']], function () {

    //Картины
    Route::get('/newPictures', [ModeratorController::class, 'newPictures'])->name('newPictures'); //Получение картин со статусом "в обработке"
    Route::put('/newPictures/{id}', [ModeratorController::class, 'acceptPicture'])->name('picture.accept'); //разрешение на продажу
    Route::post('/newPictures/{id}', [ModeratorController::class, 'rejectedPictures'])->name('picture.failures'); //отказ на продажу

});
