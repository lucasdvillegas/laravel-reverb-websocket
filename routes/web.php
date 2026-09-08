<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MessageController;

Route::inertia('/', 'Welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
    Route::get('message', [MessageController::class, 'index'])->name('message');
    Route::post('message', [MessageController::class, 'store'])->name('message.post');
});

require __DIR__.'/settings.php';
