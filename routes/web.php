<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\AdminController; 
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes - K3 STORE PROJECT (Dinamis & Admin Panel Terproteksi)
|--------------------------------------------------------------------------
*/

// =========================================================================
// RUTE UTAMA / PELANGGAN (CUSTOMER ROUTES - Bebas Diakses Siapa Saja)
// =========================================================================
Route::get('/', [TransaksiController::class, 'index'])->name('home');
Route::get('/topup/{id}', [TransaksiController::class, 'show'])->name('topup.detail');
Route::post('/topup/store', [TransaksiController::class, 'store'])->name('topup.store');
Route::get('/nota/{no_trx}', [TransaksiController::class, 'showNota'])->name('topup.nota');
Route::get('/cek-transaksi', [TransaksiController::class, 'search'])->name('transaksi.search');


// =========================================================================
// RUTE OTENTIKASI ADMIN (GUEST - Belum Login)
// =========================================================================
Route::middleware('guest')->group(function () {
    Route::get('admin/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('admin/login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.store');
});


// =========================================================================
// RUTE MANAGEMENT ADMIN (ADMIN PANEL ROUTES - Wajib Login)
// =========================================================================
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    
    // 1. Beranda / Dashboard Utama Admin (Statistik Ringkas K3STORE)
    Route::get('/dashboard', [TransaksiController::class, 'dashboardUtama'])->name('dashboard');
    
    // 2. Kelola Pesanan / Transaksi Terpisah (Halaman Tabel Lengkap)
    Route::get('/pesanan', [TransaksiController::class, 'adminDashboard'])->name('pesanan.index');
    Route::post('/update-status/{id}', [TransaksiController::class, 'updateStatus'])->name('updateStatus');
    
    // RUTE DOWNLOAD SPREADSHEET EXCEL
    Route::get('/transactions/export', [TransaksiController::class, 'exportExcel'])->name('transactions.export');

    // RUTE UNTUK KOSONGKAN SEMUA RIWAYAT TRANSAKSI (MENGGUNAKAN DELETE)
    Route::delete('/transactions/truncate', [TransaksiController::class, 'truncateTransaksi'])->name('transactions.truncate');

    // 3. CRUD Daftar Games + FITUR EDIT (DIALIKHAN KE AdminController)
    Route::get('/games', [AdminController::class, 'indexGames'])->name('games.index');
    Route::post('/games/store', [AdminController::class, 'storeGame'])->name('games.store');
    Route::put('/games/{id_game}', [AdminController::class, 'updateGame'])->name('games.update'); 
    Route::delete('/games/destroy/{id_game}', [AdminController::class, 'destroyGame'])->name('games.destroy');

    // 4. CRUD Isi Game / Nominal (DIALIKHAN KE AdminController - SINKRON MODAL & VIEW)
    Route::get('/nominal', [AdminController::class, 'indexNominal'])->name('nominal.index');
    Route::post('/nominal/store', [AdminController::class, 'storeNominal'])->name('nominal.store');
    Route::put('/nominal/update/{id}', [AdminController::class, 'updateNominal'])->name('nominal.update'); 
    Route::delete('/nominal/delete/{id}', [AdminController::class, 'destroyNominal'])->name('nominal.destroy');

    // 5. Pengaturan Kontak Pembayaran (DIALIKHAN KE AdminController)
    Route::get('/pengaturan', [AdminController::class, 'indexPengaturan'])->name('pengaturan.index');
    Route::post('/pengaturan/update', [AdminController::class, 'updatePengaturan'])->name('pengaturan.update');

    // 6. FITUR TAMBAH ADMIN BARU (Tetap di TransaksiController)
    Route::get('/register', [TransaksiController::class, 'showRegisterForm'])->name('register.form');
    Route::post('/register', [TransaksiController::class, 'storeAdmin'])->name('register.store');

    // FIX LOGOUT ADMIN
    Route::post('/logout', function (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/admin/login');
    })->name('logout');
});


// =========================================================================
// RUTE PROFILE ADMIN (AUTH - Sudah Login)
// =========================================================================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});