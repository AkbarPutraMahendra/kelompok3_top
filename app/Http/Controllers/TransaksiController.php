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
     */
    public function index()
    {
        $games = Game::all();
        return view('index', compact('games'));
    }

    /**
     * 2. LAMAN DETAIL (FORM TOPUP)
     */
    public function show($id)
    {
        // Mencari game berdasarkan primary key database (id_game)
        $game = Game::where('id_game', $id)->firstOrFail();
        
        // Ambil data nominal berdasarkan id_game untuk form pembelian dinamis
        $nominals = DB::table('nominal_games')->where('id_game', $id)->get();
        
        // AMBIL DATA PENGATURAN QRIS & NO DANA SECARA DINAMIS
        $config = DB::table('pengaturan')->first();
        
        // Jika data pengaturan belum ada pancingannya di DB, buatkan objek kosong agar blade tidak crash
        if (!$config) {
            $config = (object) [
                'no_dana' => '08123456789',
                'qris_path' => 'qris.png'
            ];
        }
        
        return view('topup_form', compact('game', 'nominals', 'config'));
    }

    /**
     * 3. PROSES SIMPAN DATA & REDIRECT KE NOTA
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'id_akun' => 'required',
            'id_game' => 'required|exists:games,id_game',
            'nominal' => 'required',
            'metode_pembayaran' => 'required',
        ]);

        try {
            DB::beginTransaction();

            // Simpan data customer
            $customer = Customer::create([
                'email'   => $request->email,
                'id_akun' => $request->id_akun,
                'id_game' => $request->id_game,
            ]);

            // Buat No. Transaksi Unik
            $no_transaksi = 'TRX-' . date('Ymd') . '-' . strtoupper(Str::random(6));

            // Simpan ke tabel transaksi
            DB::table('transaksi')->insert([
                'no_transaksi'      => $no_transaksi,
                'id_customer'       => $customer->id_customer,
                'nominal'           => $request->nominal,
                'metode_pembayaran' => $request->metode_pembayaran,
                'tanggal'           => now(),
                'status'            => 'Pending',
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);

            DB::commit();

            return redirect()->route('topup.nota', $no_transaksi);

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * 4. TAMPILAN NOTA SETELAH BELI (PERBAIKAN TOTAL: HARGA & QRIS AMAN)
     */
    public function showNota($no_trx)
    {
        // Join dikunci secara spesifik agar data transaksi tidak bentrok dengan data lain
        $nota = DB::table('transaksi')
            ->join('customers', 'transaksi.id_customer', '=', 'customers.id_customer')
            ->join('games', 'customers.id_game', '=', 'games.id_game')
            ->leftJoin('nominal_games', function($join) {
                $join->on('transaksi.nominal', '=', 'nominal_games.layanan')
                     ->on('games.id_game', '=', 'nominal_games.id_game');
            })
            ->where('transaksi.no_transaksi', $no_trx)
            ->select(
                'transaksi.*', 
                'customers.email', 
                'customers.id_akun', 
                'games.nama_game',
                'nominal_games.harga'
            )
            ->first();

        if (!$nota) {
            return redirect()->route('home');
        }

        // Ambil data pengaturan kontak & QRIS dari database secara independen
        $config = DB::table('pengaturan')->first();

        // Pengaman cadangan jika isi baris tabel pengaturan masih kosong/null
        if (!$config) {
            $config = (object) [
                'no_dana' => '0812-3456-7890',
                'qris_path' => 'qris.png'
            ];
        }

        return view('nota', compact('nota', 'config'));
    }

    /**
     * 5. FITUR CEK STATUS TRANSAKSI (SEARCH)
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

    /**
     * 6. DASHBOARD ADMIN (KELOLA PESANAN)
     */
    public function adminDashboard()
    {
        $transactions = DB::table('transaksi')
            ->join('customers', 'transaksi.id_customer', '=', 'customers.id_customer')
            ->join('games', 'customers.id_game', '=', 'games.id_game')
            ->select('transaksi.*', 'customers.email', 'customers.id_akun', 'games.nama_game')
            ->orderBy('transaksi.created_at', 'desc')
            ->get();

        return view('admin.admin_dashboard', compact('transactions'));
    }

    /**
     * 7. UPDATE STATUS TRANSAKSI (AKSI ADMIN)
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Pending,Success,Failed'
        ]);

        DB::table('transaksi')
            ->where('id_transaksi', $id) 
            ->update([
                'status' => $request->status,
                'updated_at' => now()
            ]);

        return back()->with('success', 'Status transaksi berhasil diperbarui!');
    }
}