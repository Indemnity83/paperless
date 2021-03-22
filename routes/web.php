<?php

use App\Http\Controllers\FileController;
use App\Http\Controllers\ObjectController;
use Illuminate\Support\Facades\Route;

/*
 * Dashbaord
 */
Route::view('/dashboard', 'dashboard')->middleware(['auth:sanctum', 'verified'])->name('dashboard');

/*
 * Directory Browser
 */
Route::get('/browse', ObjectController::class)->name('browse');

/*
 * File Management
 */
Route::post('/files', [FileController::class, 'store'])->name('files.store');
Route::get('/files/{file}/download', [FileController::class, 'download'])->name('files.download');
Route::get('/files/{file}/thumbnail', [FileController::class, 'thumbnail'])->name('files.thumbnail');

/*
 * Settings
 */
Route::view('/settings', 'settings.show')->middleware(['auth:sanctum', 'verified'])->name('settings');

/*
 * Redirects
 */
Route::redirect('/', '/dashboard');
