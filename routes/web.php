<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DiningTableController;

/*
|--------------------------------------------------------------------------
| Guest routes
|--------------------------------------------------------------------------
| Route di sini hanya bisa diakses user yang belum login.
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [LoginController::class, 'login'])
        ->name('login.process');

    require __DIR__ . '/members/gilang.php';
});

/*
|--------------------------------------------------------------------------
| Auth routes
|--------------------------------------------------------------------------
| Route di sini hanya bisa diakses user yang sudah login.
| Logout dan fitur umum user login taruh di sini.
*/

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])
        ->name('logout');

    /*
    |--------------------------------------------------------------------------
    | Admin routes
    |--------------------------------------------------------------------------
    | Khusus user login dengan role admin.
    */

    Route::middleware('role:admin')->group(function () {
        Route::get('/dashboard', function () {
            return view('dashboardAdmin');
        })->name('dashboard');

        Route::resource('menu', MenuController::class)
            ->parameters(['menu' => 'menuItem']);

        Route::prefix('menu')->name('menu.')->group(function () {
            Route::get('{menuItem}/quick-edit', [MenuController::class, 'editQuick'])
                ->name('quick-edit');

            Route::put('{menuItem}/quick-update', [MenuController::class, 'updateQuick'])
                ->name('quick-update');

            Route::post('import/preview', [MenuController::class, 'importPreview'])
                ->name('import.preview');

            Route::post('import/store', [MenuController::class, 'importStore'])
                ->name('import.store');

            Route::post('{menuItem}/discount', [MenuController::class, 'storeDiscount'])
                ->name('discount.store');

            Route::delete('{menuItem}/discount/{discount}', [MenuController::class, 'destroyDiscount'])
                ->name('discount.destroy');
        });

        Route::resource('categories', CategoryController::class);
        Route::resource('tables', DiningTableController::class);
    });

    /*
    |--------------------------------------------------------------------------
    | Kasir routes
    |--------------------------------------------------------------------------
    | Khusus user login dengan role kasir.
    */

    Route::middleware('role:kasir')
        ->prefix('kasir')
        ->name('kasir.')
        ->group(function () {
            Route::get('/dashboard', function () {
                return view('kasir.dashboard');
            })->name('dashboard');

            // route kasir taruh di sini nanti
        });
});

