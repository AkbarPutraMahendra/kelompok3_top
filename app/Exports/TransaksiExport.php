<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TransaksiExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    protected $tanggal;
    protected $bulan;
    protected $tahun;

    // Menangkap parameter filter dari Controller
    public function __construct($tanggal = null, $bulan = null, $tahun = null)
    {
        $this->tanggal = $tanggal;
        $this->bulan = $bulan;
        $this->tahun = $tahun;
    }

    /**
     * Query data transaksi yang akan diexport berdasarkan filter
     */
    public function query()
    {
        $query = DB::table('transaksi')
            ->join('customers', 'transaksi.id_customer', '=', 'customers.id_customer')
            ->join('games', 'customers.id_game', '=', 'games.id_game')
            ->select(
                'transaksi.no_transaksi',
                'games.nama_game',
                'customers.id_akun',
                'customers.email',
                'transaksi.nominal',
                'transaksi.metode_pembayaran',
                'transaksi.tanggal',
                'transaksi.status'
            )
            ->orderBy('transaksi.tanggal', 'desc');

        // Filter Spesifik Tanggal (YYYY-MM-DD)
        if ($this->tanggal) {
            $query->whereDate('transaksi.tanggal', $this->tanggal);
        }

        // Filter Bulan (1 - 12)
        if ($this->bulan) {
            $query->whereMonth('transaksi.tanggal', $this->bulan);
        }

        // Filter Tahun (Contoh: 2026)
        if ($this->tahun) {
            $query->whereYear('transaksi.tanggal', $this->tahun);
        }

        return $query;
    }

    /**
     * Membuat Baris Header di Spreadsheet
     */
    public function headings(): array
    {
        return [
            'No. Transaksi',
            'Nama Game',
            'ID Akun Game',
            'Email Pelanggan',
            'Nominal / Layanan',
            'Metode Pembayaran',
            'Tanggal Transaksi',
            'Status',
        ];
    }

    /**
     * Memetakan data dari database agar rapi di setiap kolom spreadsheet
     */
    public function map($transaksi): array
    {
        return [
            $transaksi->no_transaksi,
            $transaksi->nama_game,
            "'" . $transaksi->id_akun, // Diberi tanda petik tunggal (') agar ID bertipe angka panjang tidak rusak/berubah di Excel
            $transaksi->email,
            $transaksi->nominal,
            $transaksi->metode_pembayaran,
            date('d-m-Y H:i', strtotime($transaksi->tanggal)),
            $transaksi->status,
        ];
    }
}