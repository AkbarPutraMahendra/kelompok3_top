<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;
use App\Models\Customer;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    /**
     * 1. LAMAN UTAMA (KATALOG GAME)
     * Menampilkan semua game di landing page
     */
    public function index()
    {
        $allGames = Game::all();
        return view('index', compact('allGames'));
    }

    /**
     * 2. LAMAN DETAIL (FORM TOPUP)
     * Menampilkan form berdasarkan game yang diklik
     */
    public function show($id)
    {
        $game = Game::findOrFail($id); // Mencari game berdasarkan ID, jika tidak ada muncul 404
        return view('topup_form', compact('game'));
    }

    /**
     * 3. PROSES SIMPAN DATA
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'id_akun' => 'required',
            'id_game' => 'required|exists:games,id_game',
        ]);

        try {
            DB::beginTransaction();

            $customer = Customer::create([
                'email' => $request->email,
                'id_akun' => $request->id_akun,
                'id_game' => $request->id_game,
            ]);

            $no_transaksi = 'TRX-' . date('Ymd') . '-' . strtoupper(Str::random(6));

            DB::table('transaksi')->insert([
                'no_transaksi' => $no_transaksi,
                'id_customer'  => $customer->id_customer,
                'tanggal'      => now(),
                'status'       => 'Pending',
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);

            DB::commit();

            return back()->with('success', 'Pesanan berhasil dibuat! Catat Nomor Transaksi Anda: ' . $no_transaksi);

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan sistem.');
        }
    }

    /**
     * 4. FITUR CEK TRANSAKSI
     */
    public function search(Request $request)
    {
        $query = $request->input('search');
        $results = null;

        if ($query) {
            $results = DB::table('transaksi')
                ->join('customers', 'transaksi.id_customer', '=', 'customers.id_customer')
                ->join('games', 'customers.id_game', '=', 'games.id_game')
                ->where('transaksi.no_transaksi', $query)
                ->orWhere('customers.id_akun', $query)
                ->select('transaksi.*', 'games.nama_game', 'customers.id_akun')
                ->get();
        }

        return view('cek-transaksi', compact('results'));
    }
}