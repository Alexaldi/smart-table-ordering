<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerMenuController;
use App\Http\Controllers\MidtransNotificationController;

Route::get('/payment/{order}', [CustomerMenuController::class, 'payment'])
    ->name('customer-menu.payment');
Route::get('/customer-menu/{token}/order-summary/{order:order_code}', [CustomerMenuController::class, 'orderSummary'])
    ->name('customer-menu.order-summary');
Route::post('/midtrans/notification', [MidtransNotificationController::class, 'handle'])
    ->name('midtrans.notification');
Route::get('/customer-menu/{token}/order-status/{order:order_code}', [CustomerMenuController::class, 'orderStatus'])
    ->name('customer-menu.order-status');
Route::get('/customer/{token}/order/{order}/countdown', [CustomerMenuController::class, 'countdownStatus'])
    ->name('customer.order.countdown');