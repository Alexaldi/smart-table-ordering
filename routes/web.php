<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DiningTableController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ShiftController;
use App\Http\Controllers\CashierPaymentController;
use App\Http\Controllers\KitchenController;
use App\Http\Controllers\NotificationController;
use App\Models\Category;
use App\Models\DiningTable;
use App\Models\MenuItem;
use App\Models\Shift;
use App\Models\User;

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
    require __DIR__ . '/members/fatur.php';
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

    Route::prefix('notifications')
        ->name('notifications.')
        ->group(function () {
            Route::get('/', [NotificationController::class, 'index'])
                ->name('index');

            Route::get('/unread-count', [NotificationController::class, 'unreadCount'])
                ->name('unread-count');

            Route::post('/read-all', [NotificationController::class, 'readAll'])
                ->name('read-all');

            Route::post('/{notification}/read', [NotificationController::class, 'markAsRead'])
                ->name('read');
        });

    /*
    |--------------------------------------------------------------------------
    | Admin routes
    |--------------------------------------------------------------------------
    | Khusus user login dengan role admin.
    */

    Route::middleware('role:admin')->group(function () {
        Route::get('/dashboard', function () {
            return view('dashboardAdmin', [
                'totalUsers' => User::count(),
                'activeUsers' => User::where('is_active', true)->count(),
                'totalShifts' => Shift::count(),
                'totalTables' => DiningTable::count(),
                'totalCategories' => Category::count(),
                'totalMenus' => MenuItem::count(),
                'activeMenus' => MenuItem::where('is_active', true)->count(),
            ]);
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
        Route::resource('users', UserController::class);
        Route::resource('shifts', ShiftController::class);
    });

    /*
    |--------------------------------------------------------------------------
    | Owner routes
    |--------------------------------------------------------------------------
    | Khusus user login dengan role owner.
    */

    Route::middleware('role:owner')
        ->prefix('owner')
        ->name('owner.')
        ->group(function () {
            Route::get('/dashboard', function () {
                return view('owner.dashboard');
            })->name('dashboard');
        });

    /*
    |--------------------------------------------------------------------------
    | Kasir routes
    |--------------------------------------------------------------------------
    | Khusus user login dengan role kasir.
    */

    Route::middleware(['role:kasir', 'shift.active'])
        ->prefix('kasir')
        ->name('kasir.')
        ->group(function () {
            Route::get('/dashboard', [CashierPaymentController::class, 'index'])
                ->name('dashboard');

            Route::get('/dashboard/realtime', [CashierPaymentController::class, 'realtime'])
                ->name('dashboard.realtime');

            Route::get('/orders/{order}/receipt', [CashierPaymentController::class, 'receipt'])
                ->name('orders.receipt');

            Route::post('/orders/{order}/reject-items', [CashierPaymentController::class, 'rejectItems'])
                ->name('orders.reject-items');

            Route::post('/cashier/orders/{order}/pay-cash', [CashierPaymentController::class, 'payCash'])
                ->name('orders.pay-cash');
        });

    /*
    |--------------------------------------------------------------------------
    | Dapur routes
    |--------------------------------------------------------------------------
    | Khusus user login dengan role dapur.
    */

    Route::middleware(['role:dapur', 'shift.active'])
    ->prefix('dapur')
    ->name('dapur.')
    ->group(function () {
        Route::get('/dashboard', [KitchenController::class, 'index'])
            ->name('dashboard');

        Route::get('/dashboard/realtime', [KitchenController::class, 'realtime'])
            ->name('dashboard.realtime');

        Route::get('/orders/{order}', [KitchenController::class, 'show'])
            ->name('orders.show');

        Route::post('/orders/{order}/prepare', [KitchenController::class, 'prepare'])
            ->name('orders.prepare');

        Route::post('/orders/{order}/done', [KitchenController::class, 'done'])
            ->name('orders.done');

    });
});
