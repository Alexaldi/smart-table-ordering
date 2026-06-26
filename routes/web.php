<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestCommitController;
use App\Http\Controllers\Admin\CategoryController;

Route::get('/test-commit', [TestCommitController::class, 'index']);

Route::get('/dashboard', function () {
    return view('dashboardAdmin');
});

Route::resource('categories', CategoryController::class);


