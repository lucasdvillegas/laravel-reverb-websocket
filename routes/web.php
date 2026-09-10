<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\UserController;

Route::inertia('/', 'Welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
    //Route::get('message', [MessageController::class, 'index'])->name('message');
    //Route::post('message', [MessageController::class, 'store'])->name('message.post');

    Route::get('message/{user}', [MessageController::class, 'show'])->name('message.show');
    
    Route::post('message', [MessageController::class, 'store'])->name('message.store');

    Route::get('user', [UserController::class, 'index'])->name('user.index');
});

require __DIR__.'/settings.php';
