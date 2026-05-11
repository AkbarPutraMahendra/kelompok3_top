<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;
use App\Models\Customer;

class TransaksiController extends Controller
{
    /**
     * Menampilkan halaman form top-up (Data Game ditarik dari database)
     */
    public function index()
    {
        $allGames = Game::all(); // Mengambil 5 game yang kita seed tadi
        return view('topup_form', compact('allGames'));
    }

    /**
     * Menyimpan data inputan user ke tabel customers
     */
    public function store(Request $request)
    {
        // 1. Validasi input agar tidak kosong (Sesuai alur Validasi Data di DFD)
        $request->validate([
            'email' => 'required|email',
            'id_akun' => 'required',
            'id_game' => 'required|exists:games,id_game',
        ]);

        // 2. Simpan ke tabel customers
        Customer::create([
            'email' => $request->email,
            'id_akun' => $request->id_akun,
            'id_game' => $request->id_game,
        ]);

        // 3. Setelah simpan, arahkan ke halaman berikutnya (misal: Pembayaran)
        return back()->with('success', 'Data berhasil disimpan! Silakan lanjut ke pembayaran.');
    }
}