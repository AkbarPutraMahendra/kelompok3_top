<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TransaksiExport implements FromCollection, WithHeadings, WithMapping
{
    protected $tanggalMulai;
    protected $tanggalSelesai;
    protected $downloadSemua;
    protected $search;

    // Menangkap parameter filter dari Controller
    public function __construct($tanggalMulai = null, $tanggalSelesai = null, $downloadSemua = false, $search = null)
    {
        $this->tanggalMulai = $tanggalMulai;
        $this->tanggalSelesai = $tanggalSelesai;
        $this->downloadSemua = $downloadSemua;
        $this->search = $search;
    }

    /**
     * AMBIL DATA SEBAGAI COLLECTION
     */
    public function collection()
    {
        $query = DB::table('transaksi')
            ->join('customers', 'transaksi.id_customer', '=', 'customers.id_customer')
            ->join('games', 'customers.id_game', '=', 'games.id_game')
            ->select(
                'transaksi.no_transaksi', 
                'customers.email', 
                'customers.id_akun', 
                'games.nama_game', 
                'transaksi.nominal', 
                'transaksi.metode_pembayaran', 
                'transaksi.status', 
                'transaksi.tanggal'
            )
            ->orderBy('transaksi.created_at', 'desc');

        // Aplikasi Filter Keyword Pencarian jika ada
        if ($this->search) {
            $query->where(function($q) {
                $q->where('transaksi.no_transaksi', 'LIKE', "%{$this->search}%")
                  ->orWhere('customers.id_akun', 'LIKE', "%{$this->search}%")
                  ->orWhere('customers.email', 'LIKE', "%{$this->search}%");
            });
        }

        // Angkut Filter Tanggal jika tombol 'Download Semua' tidak dicentang
        if (!$this->downloadSemua) {
            if ($this->tanggalMulai && $this->tanggalSelesai) {
                $query->whereBetween('transaksi.tanggal', [
                    $this->tanggalMulai . ' 00:00:00',
                    $this->tanggalSelesai . ' 23:59:59'
                ]);
            }
        }

        return $query->get(); 
    }

    /**
     * STRUKTUR HEADERS / JUDUL KOLOM EXCEL
     */
    public function headings(): array
    {
        return [
            'No Transaksi',
            'Email Pelanggan',
            'ID Akun Game',
            'Nama Game',
            'Nominal / Layanan',
            'Metode Pembayaran',
            'Status Transaksi',
            'Tanggal & Waktu'
        ];
    }

    /**
     * MAPPING BARIS DATA AGAR SESUAI URUTAN KOLOM
     */
    public function map($transaksi): array
    {
        return [
            $transaksi->no_transaksi,
            $transaksi->email,
            $transaksi->id_akun,
            $transaksi->nama_game,
            $transaksi->nominal,
            strtoupper($transaksi->metode_pembayaran), // SUDAH FIXED: dari strupper jadi strtoupper
            $transaksi->status,
            $transaksi->tanggal,
        ];
    }
}