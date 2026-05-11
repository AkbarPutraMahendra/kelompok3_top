<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransaksiController;

// Halaman Utama (Landing Page)
Route::get('/', function () {
    return view('index');
});

// Halaman Form Top Up (Dinamis mengambil data dari database)
// URL: http://127.0.0.1:8000/topup
Route::get('/topup', [TransaksiController::class, 'index'])->name('topup.index');

// Proses Simpan Data dari Form ke Database
Route::post('/topup/store', [TransaksiController::class, 'store'])->name('topup.store');

// Halaman Cek Transaksi
Route::get('/cek-transaksi', function () {
    return view('cek-transaksi');
});