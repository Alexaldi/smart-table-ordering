<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestCommitController;

Route::get('/test-commit', [TestCommitController::class, 'index']);
Route::get('/', function () {
    return view('welcome');
});
