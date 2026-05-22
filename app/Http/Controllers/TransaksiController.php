<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;
use App\Models\Customer;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
// TAMBAHAN: Import library untuk fitur export spreadsheet
use App\Exports\TransaksiExport;
use Maatwebsite\Excel\Facades\Excel;

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

    /**
     * 8. MENYIMPAN GAME BARU & UPLOAD GAMBAR OTOMATIS BERDASARKAN ID
     */
    public function storeGame(Request $request)
    {
        // Validasi Input Form Admin
        $request->validate([
            'nama_game' => 'required|unique:games,nama_game',
            'gambar'    => 'required|image|mimes:jpeg,png,jpg,webp|max:2048', // Maksimal file gambar 2MB
        ], [
            'nama_game.required' => 'Nama game wajib diisi!',
            'nama_game.unique'   => 'Nama game ini sudah terdaftar!',
            'gambar.required'    => 'File gambar/banner wajib dipilih!',
            'gambar.image'       => 'File harus berupa gambar valid!',
            'gambar.mimes'       => 'Format gambar harus JPEG, PNG, JPG, atau WEBP!',
            'gambar.max'         => 'Ukuran gambar tidak boleh lebih dari 2MB!',
        ]);

        try {
            DB::beginTransaction();

            // 1. Simpan nama game ke database, ambil nilai ID barunya secara otomatis
            $idBaru = DB::table('games')->insertGetId([
                'nama_game'  => $request->nama_game,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 2. Upload file gambar menggunakan penamaan ID baru tersebut
            if ($request->hasFile('gambar')) {
                $file = $request->file('gambar');
                
                // Mendapatkan ekstensi file asli (png/jpg/jpeg)
                $ekstensi = $file->getClientOriginalExtension();
                
                // Menamai file sesuai ID barunya (Contoh: 6.png)
                $namaGambarBaru = $idBaru . '.' . $ekstensi;
                
                // Melempar & memindahkan file langsung ke folder public/images/
                $file->move(public_path('images'), $namaGambarBaru);
            }

            DB::commit();

            return back()->with('success', 'Game baru "' . $request->nama_game . '" berhasil disimpan otomatis dengan gambar nomor ' . $idBaru . '!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal menambah game: ' . $e->getMessage());
        }
    }

    /**
     * 9. MENJALANKAN DOWNLOAD SPREADSHEET DENGAN FILTER TANGGAL/BULAN/TAHUN
     */
    public function exportExcel(Request $request)
    {
        $tanggal = $request->input('filter_tanggal');
        $bulan   = $request->input('filter_bulan');
        $tahun   = $request->input('filter_tahun');

        // Menyusun nama file otomatis agar dinamis saat didownload
        $namaFile = 'Laporan_Transaksi';
        if ($tanggal) { $namaFile .= '_' . $tanggal; }
        if ($bulan) { $namaFile .= '_Bulan_' . $bulan; }
        if ($tahun) { $namaFile .= '_Tahun_' . $tahun; }
        $namaFile .= '.xlsx';

        return Excel::download(new TransaksiExport($tanggal, $bulan, $tahun), $namaFile);
    }

    /**
     * 10. MENGHAPUS / KOSONGKAN SEMUA RIWAYAT TRANSAKSI DAN CUSTOMER
     */
    public function truncateTransaksi()
    {
        try {
            DB::beginTransaction();

            // Mematikan pengecekan foreign key sementara agar tidak diblock database
            DB::statement('SET FOREIGN_KEY_CHECKS = 0');
            
            DB::table('transaksi')->truncate();
            DB::table('customers')->truncate();
            
            DB::statement('SET FOREIGN_KEY_CHECKS = 1');

            DB::commit();
            return back()->with('success', 'Semua riwayat transaksi dan data pelanggan berhasil dibersihkan total!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal membersihkan riwayat: ' . $e->getMessage());
        }
    }
}