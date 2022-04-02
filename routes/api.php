<?php

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\GenreController;
use App\Http\Controllers\Users\UserController;
use App\Http\Controllers\Admin\ApplicationCategoryController;
use App\Http\Controllers\Users\ApplicationController;
use App\Http\Controllers\Users\PictureController;
use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Users\SubscriptionController;


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

    require_once "auth_users/all.php";

    //Подписки
    require_once "auth_users/subscriptions/subscriptions.php";

    //Для пользователя с ролью админ
    require_once "auth_users/admin/user.php";

    //Для пользователя с ролью модератор

    //Для пользователей с ролью executor(исполнители)
    require_once "auth_users/executors/executors.php";

    //Для пользователей с ролью customer(заказчик)
    require_once "auth_users/customers/customers.php";

//Подтверждение E-mail
    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return response()->json('Вы успешно подтвердили свою почту');
    })->middleware(['throttle:6,1'])->name('verification.send');

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return response()->json('Вы успешно подтвердили свою почту');
    })->middleware(['signed'])->name('verification.verify');

});

//Для всех пользователей

//Пользователи
require_once "users/users.php";

//Картины
require_once "pictures/pictures.php";

//Заявки
require_once "applications/applications.php";



//Подтверждение E-mail

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->name('verification.notice');





