<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('employees', [UserController::class, 'index'])->middleware(['auth', 'verified'])->name('employees.index');

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
