<?php

use App\Http\Controllers\FileController;
use App\Http\Controllers\ObjectController;
use App\Models\Obj;
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


Route::get('test', function() {

    return Obj::where('parent_id', 1)
        ->select(DB::raw('objects.*,
            CASE
                WHEN objects.object_type = "folder" THEN folders.name
                WHEN objects.object_type = "file" THEN files.name
            END as name
        '))
        ->leftJoin('folders', function($join) {
            $join->on('objects.object_id', 'folders.id')
                ->where('objects.object_type', 'folder');
        })
        ->leftJoin('files', function($join) {
            $join->on('objects.object_id', 'files.id')
                ->where('objects.object_type', 'file');
        })
        ->orderBy('object_type', 'DESC')
        ->orderBy('name', 'ASC')
        ->with('object')
        ->paginate();
});
