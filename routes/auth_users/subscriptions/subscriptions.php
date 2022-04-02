<?php


use App\Http\Controllers\Users\SubscriptionController;
use Illuminate\Support\Facades\Route;

Route::post('/subscriptions', [SubscriptionController::class, 'store'])->name('subscriptions.store'); //Подписка на рассылку
Route::put('/subscriptions', [SubscriptionController::class, 'update'])->name('subscriptions.update'); //Остановка и продление подписки
