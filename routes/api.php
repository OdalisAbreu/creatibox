<?php

use App\Http\Controllers\CaptureController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/capture/{cell_phone}', [CaptureController::class, 'store'])
    ->name('capture.store');
Route::get('/capture/{cell_phone}', [CaptureController::class, 'getClient'])
    ->name('capture.getClient');
