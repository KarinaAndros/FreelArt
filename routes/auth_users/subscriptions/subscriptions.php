<?php


use App\Http\Controllers\Users\SubscriptionController;
use Illuminate\Support\Facades\Route;

Route::post('/subscriptions', [SubscriptionController::class, 'store'])->name('subscriptions.store'); //Подписка на рассылку
