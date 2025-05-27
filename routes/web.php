<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn() => view('landing'))->name('landing');
Route::get('/login', fn() => view('auth.login'))->name('login');
Route::get('/register', fn() => view('auth.register'))->name('register');

Route::middleware(['auth:orangtua'])->group(function () {
    Route::get('/dashboard', fn() => view('ppdb.dashboard'))->name('dashboard.orangtua');
    Route::get('/create-pendaftar', fn() => view('ppdb.create-pendaftar'))->name('create.pendaftar');
});

Route::post('/logout', function () {
    Auth::guard('orangtua')->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout.orangtua');

// Route::get('/', function () {
//     return view('welcome');
// });
