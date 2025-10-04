<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Laporan;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {
    Route::get('/laporan', function () {
        return response()->json([
            'message' => 'Welcome to the API version 1',
            'status' => 'success',
            'data' => Laporan::all()
        ]);
    });
});
