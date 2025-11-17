<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Api1AuthController;
use App\Http\Controllers\Api\V1\Api1LaporanContoller;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {
    Route::get('/laporan', [Api1LaporanContoller::class, 'index'])->middleware('auth:sanctum');
    Route::post('/login', [Api1AuthController::class, 'login']);
});

