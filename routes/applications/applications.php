<?php

use App\Http\Controllers\Users\ApplicationController;
use Illuminate\Support\Facades\Route;

Route::get('/applications', [ApplicationController::class, 'index'])->name('applications.index'); //Все заявки
Route::get('/applications/{id}', [ApplicationController::class, 'show'])->name('applications.show'); //Вывод одной заявки
Route::get('/main/applications', [ApplicationController::class, 'ApplicationMain'])->name('applications.main'); //Вывод двух последних заявок
