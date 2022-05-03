<?php

use App\Http\Controllers\Users\EmailController;
use App\Http\Controllers\PHPMailerController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



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
    require_once "auth_users/moderator/moderator.php";

    //Для пользователей с ролью executor(исполнители)
    require_once "auth_users/executors/executors.php";

    //Для пользователей с ролью customer(заказчик)
    require_once "auth_users/customers/customers.php";

});

//Для всех пользователей

//Пользователи
require_once "users/users.php";

//Картины
require_once "pictures/pictures.php";

//Заявки
require_once "applications/applications.php";



//Verify email
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/email/verify', [EmailController::class, 'email_verify'])->name('verification.notice');
});







