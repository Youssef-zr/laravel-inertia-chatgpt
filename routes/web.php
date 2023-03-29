<?php

use App\Http\Controllers\ChatDestroyController;
use App\Http\Controllers\ChatGptDestroyController;
use App\Http\Controllers\ChatGptIndexController;
use App\Http\Controllers\ChatGptStoreController;
use App\Http\Controllers\PresetController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;



Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/chat/{id?}', ChatGptIndexController::class)->name('chat.show');
    Route::post('/chat/{id?}', ChatGptStoreController::class)->name('chat.store');
    Route::post('/chatUpdate/updateRole', [ChatGptStoreController::class, 'updateUserRole'])->name('chat.updateRole');
    Route::delete('/chat/{chat}', ChatGptDestroyController::class)->name('chat.destroy');
    Route::get("/chat/{chat}/export", [ChatGptIndexController::class, "exportChat"])->name("chat.export");
    Route::get("/chatGpt", [ChatGptIndexController::class, "newChat"])->name("chat.fiverr");

    // preset  chatgpt params
    Route::post('preset/store', [PresetController::class, "store"])->name('preset.store');
    Route::get('presets/load', [PresetController::class, "loadPresets"])->name('preset.load');
});

require __DIR__ . '/auth.php';
