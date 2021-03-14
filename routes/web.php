<?php

use App\Http\Controllers\FileController;
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

Route::redirect('/', '/dashboard');
Route::resource('/files', FileController::class);
Route::get('/files/{file}/download', [FileController::class, 'download']);
Route::get('/files/{file}/thumbnail', [FileController::class, 'thumbnail']);

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/browse', [FileController::class, 'index'])->name('browse');
Route::get('tree', function () {
    dd(\App\Models\DirectoryTree::tree()
        ->where('object_type', 'folder')
        ->with('object')
        ->depthFirst()
        ->get());
});
