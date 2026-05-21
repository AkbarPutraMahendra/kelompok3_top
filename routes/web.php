<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Web Routes - K3 STORE PROJECT (Dinamis & Admin Panel)
|--------------------------------------------------------------------------
*/

// ==========================================
// RUTE UTAMA / PELANGGAN (CUSTOMER ROUTES)
// ==========================================

// 1. Laman Utama (Katalog Semua Game)
Route::get('/', [TransaksiController::class, 'index'])->name('home');

// 2. Laman Detail Top Up (Form Input per Game)
Route::get('/topup/{id}', [TransaksiController::class, 'show'])->name('topup.detail');

// 3. Proses Simpan Data (Action saat klik "Beli Sekarang")
Route::post('/topup/store', [TransaksiController::class, 'store'])->name('topup.store');

// 4. Halaman Nota Transaksi (Muncul setelah Berhasil Beli)
Route::get('/nota/{no_trx}', [TransaksiController::class, 'showNota'])->name('topup.nota');

// 5. Fitur Lacak Pesanan (Cek Status)
Route::get('/cek-transaksi', [TransaksiController::class, 'search'])->name('transaksi.search');


// ==========================================
// RUTE MANAGEMENT ADMIN (ADMIN PANEL ROUTES)
// ==========================================
Route::prefix('admin')->name('admin.')->group(function () {
    
    // 6. Dashboard Utama Admin & Kelola Pesanan
    Route::get('/dashboard', [TransaksiController::class, 'adminDashboard'])->name('dashboard');
    Route::post('/update-status/{id}', [TransaksiController::class, 'updateStatus'])->name('updateStatus');

    // 7. CRUD Daftar Games (Menambah/Mengedit Game di Web)
    Route::get('/games', [AdminController::class, 'indexGames'])->name('games.index');
    Route::post('/games/store', [AdminController::class, 'storeGame'])->name('games.store');
    Route::post('/games/update/{id}', [AdminController::class, 'updateGame'])->name('games.update');
    Route::delete('/games/delete/{id}', [AdminController::class, 'destroyGame'])->name('games.destroy');

    // 8. CRUD Isi Game (Varian Nominal, Harga, & Kelola Stok)
    Route::get('/nominal', [AdminController::class, 'indexNominal'])->name('nominal.index');
    Route::post('/nominal/store', [AdminController::class, 'storeNominal'])->name('nominal.store');
    Route::post('/nominal/update/{id}', [AdminController::class, 'updateNominal'])->name('nominal.update');
    Route::delete('/nominal/delete/{id}', [AdminController::class, 'destroyNominal'])->name('nominal.destroy');

    // 9. Pengaturan Kontak Pembayaran (Ubah No. DANA & QRIS)
    // Nama rute otomatis menjadi 'admin.pengaturan.index' dan 'admin.pengaturan.update'
    Route::get('/pengaturan', [AdminController::class, 'indexPengaturan'])->name('pengaturan.index');
    Route::post('/pengaturan/update', [AdminController::class, 'updatePengaturan'])->name('pengaturan.update');
    
});