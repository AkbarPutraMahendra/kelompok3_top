<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    // ==========================================
    // 1. MANAGEMENT GAMES
    // ==========================================
    
    /**
     * Menampilkan daftar semua game di dashboard admin
     */
    public function indexGames()
    {
        $games = DB::table('games')->get();
        return view('admin.games', compact('games'));
    }


    // ==========================================
    // 2. MANAGEMENT NOMINAL / VARIAN PRODUK (KONSEP BARU)
    // ==========================================
    
    /**
     * Menampilkan daftar game atau varian nominal berdasarkan game yang dipilih
     */
    public function indexNominal(Request $request)
    {
        // Ambil semua daftar game untuk ditampilkan sebagai pilihan kartu (cards)
        $games = DB::table('games')->get();
        $selectedGame = null;
        $nominals = collect();

        // Jika admin mengklik salah satu game, parameter 'game_id' akan dikirim lewat URL
        if ($request->has('game_id')) {
            $selectedGame = DB::table('games')->where('id_game', $request->game_id)->first();
            
            if ($selectedGame) {
                // Ambil daftar nominal khusus untuk game yang dipilih saja
                $nominals = DB::table('nominal_games')
                    ->where('id_game', $selectedGame->id_game)
                    ->get();
            }
        }

        return view('admin.nominal', compact('games', 'selectedGame', 'nominals'));
    }

    /**
     * Memproses penyimpanan data nominal baru ke database
     */
    public function storeNominal(Request $request)
    {
        $request->validate([
            'id_game' => 'required|exists:games,id_game',
            'layanan' => 'required|string|max:255',
            'harga'   => 'required|numeric|min:0',
        ]);

        // Insert data langsung menggunakan Query Builder
        DB::table('nominal_games')->insert([
            'id_game'    => $request->id_game,
            'layanan'    => $request->layanan,
            'harga'      => $request->harga,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Nominal baru berhasil ditambahkan ke game ini!');
    }


    // ==========================================
    // 3. MANAGEMENT PENGATURAN KONTAK & QRIS
    // ==========================================
    
    /**
     * Menampilkan Halaman Pengaturan di Admin
     */
    public function indexPengaturan() {
        $config = DB::table('pengaturan')->first();
        
        // Jika tabel kosong, otomatis generate data pancingan
        if (!$config) {
            DB::table('pengaturan')->insert([
                'id' => 1,
                'no_dana' => '08123456789',
                'qris_path' => 'qris.png',
                'created_at' => now(),
                'updated_at' => now()
            ]);
            $config = DB::table('pengaturan')->first();
        }
        
        return view('admin.pengaturan', compact('config'));
    }

    /**
     * Memproses Update QRIS dan No DANA dari Form Web Admin
     */
    public function updatePengaturan(Request $request) {
        $data = [
            'no_dana' => $request->no_dana, 
            'updated_at' => now()
        ];

        if ($request->hasFile('qris')) {
            $request->validate([
                'qris' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048'
            ]);
            
            $qrisName = 'qris_' . time() . '.' . $request->qris->getClientOriginalExtension(); 
            $request->qris->move(public_path('images'), $qrisName);
            
            $data['qris_path'] = $qrisName;
        }

        DB::table('pengaturan')->where('id', 1)->update($data);
        
        return back()->with('success', 'Kontak Pembayaran & QRIS berhasil diperbarui!');
    }
}