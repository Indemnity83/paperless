<?php

use App\Http\Controllers\DocumentController;
use App\Http\Controllers\DocumentSearchController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::redirect('/', '/documents');

Route::middleware(['auth:sanctum', 'verified'])->group(function() {
    Route::get('/documents/search', DocumentSearchController::class)->name('documents.search');
    Route::resource('/documents', DocumentController::class);
});
