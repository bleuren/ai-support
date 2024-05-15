<?php

declare(strict_types=1);

use App\Http\Controllers\SupportController;
use App\Livewire\SupportChat;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::post('/support/{team}/answer', [SupportController::class, 'answer'])->name('support.answer');
Route::get('/{team?}', SupportChat::class);
