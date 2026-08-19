<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

// Redirect root URL ke daftar tugas (atau ke login jika belum terautentikasi)
Route::get('/', function () {
    return redirect()->route('tasks.index');
});

// Rute untuk Tamu (Belum Login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Rute untuk Mahasiswa yang Terautentikasi (Sudah Login)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::resource('tasks', TaskController::class)->except(['show']);
    Route::resource('courses', CourseController::class)->except(['show', 'create', 'edit']);
});


