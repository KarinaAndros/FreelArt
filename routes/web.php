<?php

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use \App\Http\Controllers\Users\UserController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {

    return view('home');
})->name('home');



Route::get('/users/create', [PageController::class, 'create'])->name('user.create');

Route::get('/users/login/form', [PageController::class, 'login_user'])->name('user.login');
Route::group(['middleware' => ['role:admin']], function () {
    Route::get('/genres', [PageController::class, 'genres'])->name('genres');
});




