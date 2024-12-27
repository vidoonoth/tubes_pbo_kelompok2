<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('/pengusul', App\Http\Controllers\Api\PengusulController::class);
Route::apiResource('/pengusulIndex', App\Http\Controllers\Api\IndexPengusulController::class);
