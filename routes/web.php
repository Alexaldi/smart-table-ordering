<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestCommitController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DiningTableController;
use App\Http\Controllers\CustomerMenuController;

Route::get('/test-commit', [TestCommitController::class, 'index']);

Route::get('/dashboard', function () {
    return view('dashboardAdmin');
})->name('dashboard');


Route::resource('menu', MenuController::class)
    ->parameters(['menu' => 'menuItem']);
Route::get('menu/{menuItem}/quick-edit', [MenuController::class, 'editQuick'])
    ->name('menu.quick-edit');
Route::put('menu/{menuItem}/quick-update', [MenuController::class, 'updateQuick'])
    ->name('menu.quick-update');
Route::post('menu/import/preview', [MenuController::class, 'importPreview'])
    ->name('menu.import.preview');
Route::post('menu/import/store', [MenuController::class, 'importStore'])
    ->name('menu.import.store');;

Route::resource('categories', CategoryController::class);

Route::resource('tables', DiningTableController::class);

// Customer Routes
Route::get('/customer-menu', [CustomerMenuController::class, 'index'])
    ->name('customer-menu.index');
Route::get('/customer-menu/search', [CustomerMenuController::class, 'search'])
    ->name('customer-menu.search');
Route::get('/customer-menu/cart', [CustomerMenuController::class, 'cart'])
    ->name('customer-menu.cart');