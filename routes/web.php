<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\AccesoController;

Route::get('/video/form', [VideoController::class, 'form'])->name('video.form');
Route::post('/video/validate', [VideoController::class, 'validateAccess'])->name('video.validate');

Route::get('/video/key', [VideoController::class, 'key'])->name('video.key');

Route::get('/video/stream/{file}', [VideoController::class, 'stream'])
    ->where('file', '.*')
    ->name('video.stream');

Route::get('/video/player', function () {
    if (!session()->has('playlistUrl')) {
        return redirect()->route('video.form');
    }

    return view('video.player', [
        'playlistUrl' => session('playlistUrl')
    ]);

})->name('video.player');

Route::post('/accesos', [AccesoController::class, 'store'])->name('accesos.store');
Route::get('/accesos', [AccesoController::class, 'index'])->name('accesos.index');
// Route::get('/accesos/{id}/edit', [AccesoController::class, 'edit'])->name('accesos.edit');
// Route::put('/accesos/{id}', [AccesoController::class, 'update'])->name('accesos.update');
// Route::delete('/accesos/{id}', [AccesoController::class, 'destroy'])->name('accesos.destroy');