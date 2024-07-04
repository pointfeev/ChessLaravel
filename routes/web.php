<?php

use App\Http\Controllers\PlayController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Welcome')
    ->name('welcome');

Route::controller(PlayController::class)
    ->name('play')
    ->prefix('play')
    ->group(function () {
        Route::get('/', 'index');

        Route::post('/reset', 'reset')
            ->name('.reset');

        Route::post('/move', 'move')
            ->name('.move');
    });

Route::fallback(function () {
    return redirect(route('welcome', absolute: false));
});
