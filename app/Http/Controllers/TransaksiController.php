<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;
use App\Models\Customer;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Exports\TransaksiExport;
use Maatwebsite\Excel\Facades\Excel;

class TransaksiController extends Controller
{
    /**
     * =========================================================================
     * HALAMAN UTAMA & OPERASIONAL PELANGGAN (CUSTOMER SIDE)
     * =========================================================================
     */

    /**
     * 1. LAMAN UTAMA (KATALOG GAME)
     */
    public function index()
    {
        $games = Game::all();
        
        // Ambil konfigurasi kontak aktif untuk keperluan tombol WhatsApp Customer Service
        $config = DB::table('pengaturan')->first();
        
        // Jaga-jaga jika database pengaturan masih kosong di awal instalasi
        if (!$config) {
            $config = (object) [
                'no_dana' => '08123456789',
                'qris_path' => 'qris.png'
            ];
        }

        return view('index', compact('games', 'config'));
    }

    /**
     * 2. LAMAN DETAIL (FORM TOPUP)
     */
    public function show($id)
    {
        $game = Game::where('id_game', $id)->firstOrFail();
        $nominals = DB::table('nominal_games')->where('id_game', $id)->get();
        $config = DB::table('pengaturan')->first();
        
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
            'email'             => 'required|email',
            'user_id'           => 'required',
            'zone_id'           => 'nullable', // Diubah ke nullable agar mendukung game tanpa server (FF / Roblox)
            'id_game'           => 'required|exists:games,id_game',
            'nominal'           => 'required',
            'metode_pembayaran' => 'required',
        ]);

        try {
            DB::beginTransaction();

            // Kondisional format ID akun jika zone_id diisi ataupun kosong
            if ($request->filled('zone_id')) {
                $formatIdAkun = $request->user_id . ' (' . $request->zone_id . ')';
            } else {
                $formatIdAkun = $request->user_id;
            }

            $customer = Customer::create([
                'email'   => $request->email,
                'id_akun' => $formatIdAkun, 
                'id_game' => $request->id_game,
            ]);

            $no_transaksi = 'TRX-' . date('Ymd') . '-' . strtoupper(Str::random(6));

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
     * 4. TAMPILAN NOTA SETELAH BELI
     */
    public function showNota($no_trx)
    {
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

        $config = DB::table('pengaturan')->first();

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
     * =========================================================================
     * PANEL UTAMA & MANAJEMEN DATA ADMIN (ADMIN MANAGEMENT SIDE)
     * =========================================================================
     */

    /**
     * Menampilkan Halaman Ringkasan/Dashboard Utama
     */
    public function dashboardUtama()
    {
        $totalTransaksi = DB::table('transaksi')->count();
        $transaksiSukses = DB::table('transaksi')->where('status', 'Success')->count();
        $transaksiPending = DB::table('transaksi')->where('status', 'Pending')->count();
        $totalGames = DB::table('games')->count();

        // Mengambil 5 antrean transaksi terupdate
        $recentTransactions = DB::table('transaksi')
            ->join('customers', 'transaksi.id_customer', '=', 'customers.id_customer')
            ->join('games', 'customers.id_game', '=', 'games.id_game')
            ->select('transaksi.*', 'customers.id_akun', 'games.nama_game')
            ->orderBy('transaksi.created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.admin_dashboard', compact(
            'totalTransaksi', 
            'transaksiSukses', 
            'transaksiPending', 
            'totalGames', 
            'recentTransactions'
        ));
    }

    /**
     * Kelola Pesanan (Tabel transaksi lengkap dengan Filter, Opsi Rentang Tanggal & Search)
     */
    public function adminDashboard(Request $request)
    {
        $query = DB::table('transaksi')
            ->join('customers', 'transaksi.id_customer', '=', 'customers.id_customer')
            ->join('games', 'customers.id_game', '=', 'games.id_game')
            ->select('transaksi.*', 'customers.email', 'customers.id_akun', 'games.nama_game');

        // Filter Pencarian
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('transaksi.no_transaksi', 'LIKE', "%{$search}%")
                  ->orWhere('customers.id_akun', 'LIKE', "%{$search}%")
                  ->orWhere('customers.email', 'LIKE', "%{$search}%");
            });
        }

        // Filter Rentang Tanggal (Kecuali jika diceklis 'download_semua')
        if (!$request->has('download_semua')) {
            if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
                $query->whereBetween('transaksi.tanggal', [
                    $request->input('tanggal_mulai') . ' 00:00:00',
                    $request->input('tanggal_selesai') . ' 23:59:59'
                ]);
            }
        }

        $transactions = $query->orderBy('transaksi.created_at', 'desc')->get();

        return view('admin.pesanan', compact('transactions'));
    }

    /**
     * UPDATE STATUS TRANSAKSI (PENDING/SUCCESS/FAILED)
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
     * SIMPAN GAME BARU & AUTO-FORMAT GAMBAR (.PNG)
     */
    public function storeGame(Request $request)
    {
        $request->validate([
            'nama_game' => 'required|unique:games,nama_game',
            'gambar'    => 'required|image|mimes:jpeg,png,jpg,webp|max:2048', 
        ]);

        try {
            DB::beginTransaction();

            $idBaru = DB::table('games')->insertGetId([
                'nama_game'  => $request->nama_game,
                'gambar'     => '',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            if ($request->hasFile('gambar')) {
                $file = $request->file('gambar');
                $namaGambarBaru = $idBaru . '.png';
                $file->move(public_path('images'), $namaGambarBaru);

                DB::table('games')->where('id_game', $idBaru)->update(['gambar' => $namaGambarBaru]);
            }

            DB::commit();
            return back()->with('success', 'Game baru berhasil disimpan!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal menambah game: ' . $e->getMessage());
        }
    }

    /**
     * FITUR UPDATE/EDIT DATA GAME
     */
    public function updateGame(Request $request, $id)
    {
        $request->validate([
            'nama_game' => 'required|string|max:255|unique:games,nama_game,' . $id . ',id_game',
            'gambar'    => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'nama_game.unique' => 'Nama game tersebut sudah digunakan oleh katalog lain!',
            'gambar.max'        => 'Ukuran logo game maksimal adalah 2MB.'
        ]);

        try {
            $game = DB::table('games')->where('id_game', $id)->first();

            if (!$game) {
                return back()->with('error', 'Data game tidak ditemukan!');
            }

            $namaGambar = $game->id_game . '.png';

            if ($request->hasFile('gambar')) {
                $file = $request->file('gambar');
                $destinationPath = public_path('images');

                if (!empty($game->gambar) && file_exists($destinationPath . '/' . $game->gambar)) {
                    @unlink($destinationPath . '/' . $game->gambar);
                }

                $file->move($destinationPath, $namaGambar);
                
                DB::table('games')->where('id_game', $id)->update(['gambar' => $namaGambar]);
            }

            DB::table('games')
                ->where('id_game', $id)
                ->update([
                    'nama_game'  => $request->nama_game,
                    'updated_at' => now(),
                ]);

            return back()->with('success', 'Data game berhasil diperbarui!');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui data game: ' . $e->getMessage());
        }
    }

    /**
     * PRODUK GAME (CRUD NOMINAL)
     */
    public function storeNominal(Request $request)
    {
        $request->validate([
            'id_game' => 'required|exists:games,id_game',
            'layanan' => 'required',
            'harga'   => 'required|numeric',
        ]);

        DB::table('nominal_games')->insert([
            'id_game'    => $request->id_game,
            'layanan'    => $request->layanan,
            'harga'      => $request->harga,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return back()->with('success', 'Data nominal game berhasil ditambahkan!');
    }

    /**
     * UPDATE DATA NOMINAL PRODUK GAME (CRUD NOMINAL)
     */
    public function updateNominal(Request $request, $id)
    {
        $request->validate([
            'layanan' => 'required',
            'harga'   => 'required|numeric',
        ]);

        DB::table('nominal_games')
            ->where('id_nominal', $id)
            ->update([
                'layanan'    => $request->layanan,
                'harga'      => $request->harga,
                'updated_at' => now()
            ]);

        return back()->with('success', 'Nominal game berhasil diperbarui!');
    }

    /**
     * HAPUS NOMINAL PRODUK GAME (CRUD NOMINAL)
     */
    public function destroyNominal($id)
    {
        DB::table('nominal_games')->where('id_nominal', $id)->delete();
        return back()->with('success', 'Nominal game berhasil dihapus!');
    }

    /**
     * DOWNLOAD DATA LAPORAN EXCEL
     */
    public function exportExcel(Request $request)
    {
        $search         = $request->input('search');
        $downloadSemua  = $request->has('download_semua');
        $tanggalMulai   = $request->input('tanggal_mulai');
        $tanggalSelesai = $request->input('tanggal_selesai');

        $namaFile = 'Laporan_Transaksi';
        if ($downloadSemua) {
            $namaFile .= '_Semua_Waktu';
        } elseif ($tanggalMulai && $tanggalSelesai) {
            $namaFile .= '_' . $tanggalMulai . '_s_d_' . $tanggalSelesai;
        }
        if ($search) {
            $namaFile .= '_Keyword_' . Str::slug($search);
        }
        $namaFile .= '.xlsx';

        return Excel::download(new TransaksiExport($tanggalMulai, $tanggalSelesai, $downloadSemua, $search), $namaFile);
    }

    /**
     * KOSONGKAN TOTAL SEMUA RIWAYAT TRANSAKSI (TRUNCATE) - FIXED ERROR
     */
    public function truncateTransaksi()
    {
        try {
            DB::beginTransaction();

            $this->disableForeignKeys();
            DB::table('transaksi')->truncate();
            DB::table('customers')->truncate();
            $this->enableForeignKeys();

            DB::commit();
            return back()->with('success', 'Semua riwayat transaksi berhasil dibersihkan total!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal membersihkan riwayat: ' . $e->getMessage());
        }
    }

    /**
     * HAPUS DATA GAME DAN FILE GAMBARNYA
     */
    public function destroyGame($id)
    {
        try {
            $game = DB::table('games')->where('id_game', $id)->first();

            if (!$game) {
                return redirect()->back()->with('error', 'Data game tidak ditemukan!');
            }

            if (!empty($game->gambar)) {
                $imagePath = public_path('images/' . $game->gambar);
                if (file_exists($imagePath)) {
                    @unlink($imagePath);
                }
            }

            DB::table('games')->where('id_game', $id)->delete();
            return redirect()->back()->with('success', 'Game berhasil dihapus dari katalog!');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Game gagal dihapus! Data masih terikat dengan data lain.');
        }
    }

    /**
     * MENAMPILKAN MASING-MASING HALAMAN UTAMA BACKEND ADMIN
     */
    public function indexGames()
    {
        $games = Game::all();
        return view('admin.games', compact('games')); 
    }

    /**
     * FIX SINKRONISASI NOMINAL
     */
    public function indexNominal(Request $request)
    {
        $games = Game::all();
        $gameId = $request->query('game_id');
        $selectedGame = null;

        if ($gameId) {
            $selectedGame = Game::where('id_game', $gameId)->first();

            $nominals = DB::table('nominal_games')
                ->join('games', 'nominal_games.id_game', '=', 'games.id_game')
                ->where('nominal_games.id_game', $gameId)
                ->select('nominal_games.*', 'games.nama_game')
                ->get();
        } else {
            $nominals = DB::table('nominal_games')
                ->join('games', 'nominal_games.id_game', '=', 'games.id_game')
                ->select('nominal_games.*', 'games.nama_game')
                ->get();
        }

        return view('admin.nominal', compact('nominals', 'games', 'selectedGame'));
    }

    /**
     * UPDATE SETTING KONTAK / QRIS PEMBAYARAN K3STORE
     */
    public function updatePengaturan(Request $request)
    {
        $request->validate([
            'no_dana' => 'required',
            'qris'    => 'nullable|image|mimes:png,jpg,jpeg|max:2048'
        ]);

        $data = ['no_dana' => $request->no_dana];

        if ($request->hasFile('qris')) {
            $file = $request->file('qris');
            $namaQris = 'qris.png';
            $file->move(public_path('images'), $namaQris);
            $data['qris_path'] = $namaQris;
        }

        $check = DB::table('pengaturan')->first();
        if ($check) {
            DB::table('pengaturan')->where('id', $check->id)->update($data);
        } else {
            DB::table('pengaturan')->insert($data);
        }

        return back()->with('success', 'Pengaturan berhasil diperbarui!');
    }

    public function indexPengaturan()
    {
        $config = DB::table('pengaturan')->first();
        return view('admin.pengaturan', compact('config'));
    }

    /**
     * Menampilkan halaman form pendaftaran admin baru
     */
    public function showRegisterForm()
    {
        return view('admin.register_admin');
    }

    /**
     * Memproses data form pendaftaran admin ke database
     */
    public function storeAdmin(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'email.unique'       => 'Email ini sudah terdaftar sebagai admin!',
            'password.min'       => 'Password minimal harus 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.'
        ]);

        try {
            DB::table('users')->insert([
                'name'       => $request->name,
                'email'      => $request->email,
                'password'   => bcrypt($request->password),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return redirect()->route('admin.dashboard')->with('success', 'Akun Admin baru berhasil dibuat!');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membuat akun admin: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Helper internal untuk mempermudah handling database driver
     */
    private function disableForeignKeys()
    {
        $driver = DB::getDriverName();
        if ($driver === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = OFF');
        } elseif ($driver === 'pgsql') {
            DB::statement('SET CONSTRAINTS ALL DEFERRED');
        } else {
            DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        }
    }

    private function enableForeignKeys()
    {
        $driver = DB::getDriverName();
        if ($driver === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = ON');
        } elseif ($driver === 'pgsql') {
            DB::statement('SET CONSTRAINTS ALL IMMEDIATE');
        } else {
            DB::statement('SET FOREIGN_KEY_CHECKS = 1');
        }
    }
}