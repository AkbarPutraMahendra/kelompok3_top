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

    /**
     * Memproses penyimpanan data game baru dari admin
     */
    public function storeGame(Request $request)
    {
        $request->validate([
            'nama_game' => 'required|string|max:255',
            'tipe_form' => 'required|string|in:single,double', // Memastikan pilihan sesuai opsi
        ]);

        // Proses insert data menggunakan Query Builder
        DB::table('games')->insert([
            'nama_game' => $request->nama_game,
            'tipe_form' => $request->tipe_form, // Menyimpan tipe form (single/double)
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Game baru berhasil ditambahkan!');
    }

    /**
     * Memproses perubahan/update data game dari admin beserta gambarnya
     */
    public function updateGame(Request $request, $id_game)
    {
        $request->validate([
            'nama_game'   => 'required|string|max:255',
            'tipe_form'   => 'required|string|in:single,double',
            'gambar_game' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // Validasi gambar (opsional saat edit)
        ]);

        // 1. Proses update data teks berdasarkan id_game
        DB::table('games')->where('id_game', $id_game)->update([
            'nama_game' => $request->nama_game,
            'tipe_form' => $request->tipe_form, // Memperbarui tipe form pilihan admin
            'updated_at' => now(),
        ]);

        // 2. Cek apakah admin mengunggah file gambar baru
        if ($request->hasFile('gambar_game')) {
            // Menggunakan format penamaan default id_game Anda (Contoh: 1.png)
            $imageName = $id_game . '.' . $request->gambar_game->getClientOriginalExtension();
            
            // Pindahkan file gambar ke folder public/images
            $request->gambar_game->move(public_path('images'), $imageName);
        }

        return back()->with('success', 'Data game dan logo berhasil diperbarui!');
    }

    /**
     * Menghapus game beserta relasinya (Opsional)
     */
    public function destroyGame($id_game)
    {
        DB::table('games')->where('id_game', $id_game)->delete();
        return back()->with('success', 'Game berhasil dihapus!');
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

    /**
     * Memproses pembaruan/update data nominal game
     */
    public function updateNominal(Request $request, $id)
    {
        $request->validate([
            'id_game' => 'required|exists:games,id_game',
            'layanan' => 'required|string|max:255',
            'harga'   => 'required|numeric|min:0',
        ]);

        // FIX: Menggunakan 'id_nominal' sesuai struktur primary key tabel nominal_games Anda
        DB::table('nominal_games')->where('id_nominal', $id)->update([
            'id_game'    => $request->id_game,
            'layanan'    => $request->layanan,
            'harga'      => $request->harga,
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Varian nominal berhasil diperbarui!');
    }

    /**
     * Menghapus data nominal game
     */
    public function destroyNominal($id)
    {
        // FIX: Menggunakan 'id_nominal' sesuai struktur primary key tabel nominal_games Anda
        DB::table('nominal_games')->where('id_nominal', $id)->delete();
        return back()->with('success', 'Varian nominal berhasil dihapus!');
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