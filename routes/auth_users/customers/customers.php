<?php


use App\Http\Controllers\Users\ApplicationController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['role:customer']], function () {

    //Applications
    Route::post('/applications', [ApplicationController::class, 'store'])->name('applications.store'); //Добавление заявок
    Route::put('/applications/{id}', [ApplicationController::class, 'update'])->name('applications.update'); //Изменение заявок
    Route::delete('/applications/{id}', [ApplicationController::class, 'destroy'])->name('applications.destroy'); //Удаление заявок


});
