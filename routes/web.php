<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])
    ->name('home');

Route::get('/calendar', [HomeController::class, 'indexCal'])
    ->name('calendar');

Route::get('/event/{id}', [HomeController::class, 'indexCal'])
    ->where('id', '[0-9]+')
    ->name('event.show');

Route::get('language/{locale}', [LanguageController::class, 'setLanguage'])
    ->name('language.set');

/**
 * Admins Only Functions (e.g. delete & create users)
 */
Route::middleware(['auth', AdminMiddleware::class])->group(function () {

});

/**
 * User Functions (e.g. create events, edit profile)
 */
Route::middleware('auth')->group(function () {
    /* Profile routes */
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');
    Route::patch('/profile/account', [ProfileController::class, 'updateAccount'])
        ->name('profile.update.acc');
    Route::patch('/profile/user', [ProfileController::class, 'updateProfile'])
        ->name('profile.update.pro');
    Route::delete('/profile/delete', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    /* event routes */
    Route::get('/event/create', [EventController::class, 'create'])
        ->name('event.add');
    Route::post('/event/create', [EventController::class, 'store'])
        ->name('event.store');

    Route::get('/event/{id}/edit', [EventController::class, 'edit'])
        ->where('id', '[0-9]+')
        ->name('event.edit');
    Route::patch('/event/{id}/edit', [EventController::class, 'update'])
        ->where('id', '[0-9]+')
        ->name('event.update');

    Route::delete('/event/{id}/delete', [EventController::class, 'destroy'])
        ->where('id', '[0-9]+')
        ->name('event.delete');
});

require __DIR__.'/auth.php';
