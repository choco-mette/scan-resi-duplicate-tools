<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Scanner;
use App\Livewire\Dashboard;
use App\Livewire\ImportExport;
use App\Livewire\History;
use App\Livewire\Login;

Route::get('/login', Login::class)->name('login');
Route::post('/logout', function () {
    \Illuminate\Support\Facades\Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/', Scanner::class)->name('scanner');
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/import', ImportExport::class)->name('import');
    Route::get('/history', History::class)->name('history');
});
