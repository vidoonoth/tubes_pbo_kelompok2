<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PengusulController;

Route::get('pengusul', [PengusulController::class, 'index'])->name('pengusul');  // API untuk mengambil data
Route::post('pengusul', [PengusulController::class, 'store'])->name('pengusul'); // API untuk menyimpan data


// Route::get('/', function () {
//     return view('welcome');
// });
