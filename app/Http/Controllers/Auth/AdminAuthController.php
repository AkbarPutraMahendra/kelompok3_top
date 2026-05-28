<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminAuthController extends Controller
{
    /**
     * Tampilkan Halaman Login K3STORE
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');
    }

    /**
     * Proses Validasi Login Admin
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ], [
            'email.required'    => 'Email wajib diisi!',
            'email.email'       => 'Format email tidak valid!',
            'password.required' => 'Password wajib diisi!',
        ]);

        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors([
            'login_error' => 'Email atau password yang Anda masukkan salah.',
        ])->withInput($request->only('email'));
    }

    /**
     * Proses Logout Admin
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('success', 'Anda berhasil logout dari K3STORE.');
    }

    /**
     * Halaman Dashboard Utama Admin (Statistik Ringkas K3STORE)
     */
    public function dashboard()
    {
        // Hitung ringkasan data statistik untuk dashboard
        $totalTransaksi = DB::table('transaksi')->count();
        $transaksiSukses = DB::table('transaksi')->where('status', 'Success')->count();
        $transaksiPending = DB::table('transaksi')->where('status', 'Pending')->count();
        $totalGames = DB::table('games')->count();

        // Ambil 5 transaksi terbaru masuk
        $recentTransactions = DB::table('transaksi')
            ->join('customers', 'transaksi.id_customer', '=', 'customers.id_customer')
            ->join('games', 'customers.id_game', '=', 'games.id_game')
            ->select('transaksi.*', 'customers.id_akun', 'games.nama_game')
            ->orderBy('transaksi.created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard_utama', compact(
            'totalTransaksi', 
            'transaksiSukses', 
            'transaksiPending', 
            'totalGames',
            'recentTransactions'
        ));
    }
}