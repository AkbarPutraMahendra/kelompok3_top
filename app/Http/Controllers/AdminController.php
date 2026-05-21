<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    // ==========================================
    // MANAGEMENT GAMES
    // ==========================================
    public function indexGames() {
        $games = DB::table('games')->get();
        return view('admin.games', compact('games'));
    }

    public function storeGame(Request $request) {
        $request->validate([
            'nama_game' => 'required',
            'gambar' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        // Proses upload gambar logo game
        $imageName = time().'.'.$request->gambar->extension();  
        $request->gambar->move(public_path('images'), $imageName);

        DB::table('games')->insert([
            'nama_game' => $request->nama_game,
            // Jika kolom di DB Anda butuh image path/nama file silakan sesuaikan
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return back()->with('success', 'Game berhasil ditambahkan!');
    }

    // ==========================================
    // MANAGEMENT NOMINAL, HARGA & STOK
    // ==========================================
    public function indexNominal() {
        $games = DB::table('games')->get();
        $nominals = DB::table('nominal_games')
            ->join('games', 'nominal_games.id_game', '=', 'games.id_game')
            ->select('nominal_games.*', 'games.nama_game')
            ->get();
        return view('admin.nominal', compact('nominals', 'games'));
    }

    public function storeNominal(Request $request) {
        $request->validate([
            'id_game' => 'required',
            'layanan' => 'required',
            'harga' => 'required|numeric',
            'stok' => 'required|numeric',
        ]);

        DB::table('nominal_games')->insert([
            'id_game' => $request->id_game,
            'layanan' => $request->layanan,
            'harga' => $request->harga,
            'stok' => $request->stok,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return back()->with('success', 'Nominal & Stok berhasil ditambahkan!');
    }

    public function updateNominal(Request $request, $id) {
        DB::table('nominal_games')->where('id_nominal', $id)->update([
            'layanan' => $request->layanan,
            'harga' => $request->harga,
            'stok' => $request->stok,
            'updated_at' => now()
        ]);
        return back()->with('success', 'Data berhasil diperbarui!');
    }

    public function destroyNominal($id) {
        DB::table('nominal_games')->where('id_nominal', $id)->delete();
        return back()->with('success', 'Data berhasil dihapus!');
    }

    // ==========================================
    // MANAGEMENT PENGARTURAN KONTAK
    // ==========================================
    public function indexPengaturan() {
        $config = DB::table('pengaturan')->first();
        return view('admin.pengaturan', compact('config'));
    }

    public function updatePengaturan(Request $request) {
        $data = ['no_dana' => $request->no_dana, 'updated_at' => now()];

        if ($request->hasFile('qris')) {
            $request->validate(['qris' => 'image|mimes:jpeg,png,jpg|max:2048']);
            $qrisName = 'qris_live.'.$request->qris->extension();  
            $request->qris->move(public_path('images'), $qrisName);
            $data['qris_path'] = $qrisName;
        }

        DB::table('pengaturan')->where('id', 1)->update($data);
        return back()->with('success', 'Kontak Pembayaran berhasil diperbarui!');
    }
}