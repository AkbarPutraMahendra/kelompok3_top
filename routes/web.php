<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransaksiController;

/*
|--------------------------------------------------------------------------
| Web Routes - Kelompok 3 Store Project
|--------------------------------------------------------------------------
*/

// 1. Laman Utama (Katalog Semua Game)
// Menampilkan semua daftar game yang ada di database
Route::get('/', [TransaksiController::class, 'index'])->name('home');

// 2. Laman Detail Top Up (Spesifik per Game)
// Contoh URL: http://127.0.0.1:8000/topup/1
Route::get('/topup/{id}', [TransaksiController::class, 'show'])->name('topup.detail');

// 3. Proses Simpan Data (Action dari Form)
Route::post('/topup/store', [TransaksiController::class, 'store'])->name('topup.store');

// 4. Halaman Cek Transaksi (Fitur Tracking)
// Nantinya ini akan memanggil fungsi search di Controller
Route::get('/cek-transaksi', [TransaksiController::class, 'search'])->name('transaksi.search');