<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CaptureController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/admin', [AdminController::class, 'index']);
    Route::get('/admin/export/excel', [AdminController::class, 'exportExcel']);
    Route::get('/admin/export/pdf', [AdminController::class, 'exportPdf']);
});

require __DIR__ . '/auth.php';

Route::get('/capture/{cell_phone}', [CaptureController::class, 'showForm'])
    ->name('capture.form');

Route::post('/capture/image/{cell_phone}', [CaptureController::class, 'submitImage'])
    ->name('capture.submitImage');
