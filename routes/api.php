<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FileController;
use Illuminate\Support\Facades\Route;

Route::post('/authorization', [AuthController::class, 'login'])->name('login');
Route::post('/registration', [AuthController::class, 'register'])->name('register');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/logout', [AuthController::class, 'logout']);

    Route::get('/files/disk', [FileController::class, 'index']);
    Route::post('/files', [FileController::class, 'store']);
    Route::patch('/files/{file}', [FileController::class, 'update']);
    Route::delete('/files/{file}', [FileController::class, 'destroy']);
    Route::get('/files/{file}', [FileController::class, 'download']);
});
