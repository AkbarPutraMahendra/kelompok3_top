<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransaksiController;

/*
|--------------------------------------------------------------------------
| Web Routes - Kelompok 3 Store Project
|--------------------------------------------------------------------------
*/

// 1. Laman Utama (Katalog Semua Game)
Route::get('/', [TransaksiController::class, 'index'])->name('home');

// 2. Laman Detail Top Up (Form Input per Game)
Route::get('/topup/{id}', [TransaksiController::class, 'show'])->name('topup.detail');

// 3. Proses Simpan Data (Action saat klik "Beli Sekarang")
Route::post('/topup/store', [TransaksiController::class, 'store'])->name('topup.store');

// 4. Halaman Nota Transaksi (Muncul setelah Berhasil Beli)
// Menggunakan parameter {no_trx} untuk menampilkan data spesifik
Route::get('/nota/{no_trx}', [TransaksiController::class, 'showNota'])->name('topup.nota');

// 5. Fitur Lacak Pesanan (Cek Status)
Route::get('/cek-transaksi', [TransaksiController::class, 'search'])->name('transaksi.search');

// 6. Laman Admin (Kelola Transaksi)
Route::get('/admin/dashboard', [TransaksiController::class, 'adminDashboard'])->name('admin.dashboard');
Route::post('/admin/update-status/{id}', [TransaksiController::class, 'updateStatus'])->name('admin.updateStatus');