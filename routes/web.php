<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])
    ->name('home');

Route::get('language/{locale}', [LanguageController::class, 'setLanguage'])
    ->name('language.set');

/**
 * Admins Only Functions (e.g. delete users)
 */
Route::middleware(['auth', AdminMiddleware::class])->group(function () {

});

/**
 * User Functions (e.g. create events, edit profile)
 */
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile/account', [ProfileController::class, 'updateAccount'])->name('profile.update.acc');
    Route::patch('/profile/user', [ProfileController::class, 'updateProfile'])->name('profile.update.pro');
    Route::delete('/profile/delete', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
