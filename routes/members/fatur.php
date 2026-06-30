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