<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestCommitController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DiningTableController;
use App\Http\Controllers\CustomerMenuController;

// Redirect halaman awal ke dashboard admin
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Halaman dashboard admin
Route::get('/dashboard', function () {
    return view('dashboardAdmin');
})->name('dashboard');

// Route CRUD menu
Route::resource('menu', MenuController::class)
    ->parameters(['menu' => 'menuItem']);

// Route tambahan untuk fitur menu
Route::get('menu/{menuItem}/quick-edit', [MenuController::class, 'editQuick'])
    ->name('menu.quick-edit');

Route::put('menu/{menuItem}/quick-update', [MenuController::class, 'updateQuick'])
    ->name('menu.quick-update');

Route::post('menu/import/preview', [MenuController::class, 'importPreview'])
    ->name('menu.import.preview');

Route::post('menu/import/store', [MenuController::class, 'importStore'])
    ->name('menu.import.store');

Route::post('menu/{menuItem}/discount', [MenuController::class, 'storeDiscount'])
    ->name('menu.discount.store');

Route::delete('menu/{menuItem}/discount/{discount}', [MenuController::class, 'destroyDiscount'])
    ->name('menu.discount.destroy');

// Route CRUD kategori
Route::resource('categories', CategoryController::class);

// Route CRUD meja makan
Route::resource('tables', DiningTableController::class);

// Route untuk halaman guest/customer
require __DIR__ . '/members/gilang.php';