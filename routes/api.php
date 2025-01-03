<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('/pengusul', App\Http\Controllers\Api\PengusulController::class);
Route::apiResource('/usulan', App\Http\Controllers\Api\UsulanController::class);
Route::apiResource('/buku', App\Http\Controllers\Api\BukuController::class);
Route::apiResource('/pengusulIndex', App\Http\Controllers\Api\IndexPengusulController::class);
