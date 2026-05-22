@extends('layouts.admin')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4 mb-8">
        <div>
            <h2 class="text-2xl font-black uppercase italic tracking-widest text-[#fbbf24]">Kelola Pesanan</h2>
            <p class="text-xs text-gray-500 mt-1 uppercase tracking-wider">Daftar transaksi masuk sistem K3STORE</p>
        </div>
        <div class="flex items-center gap-6 self-end sm:self-auto w-full sm:w-auto justify-between sm:justify-end">
            <form action="{{ route('admin.transactions.truncate') }}" method="POST" onsubmit="return confirm('PERINGATAN! Tindakan ini akan menghapus SELURUH riwayat transaksi secara permanen dari database. Lanjutkan?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600/10 hover:bg-red-600 border border-red-500/20 text-red-500 hover:text-white text-[10px] font-black uppercase tracking-wider py-2.5 px-4 rounded-xl transition-all shadow-md active:scale-95 flex items-center gap-2">
                    <i class="fa fa-trash-alt text-xs"></i> Clear Riwayat
                </button>
            </form>

            <div class="text-right">
                <p class="text-[10px] text-gray-500 uppercase font-bold">Total Transaksi</p>
                <p class="text-2xl font-black text-[#fbbf24]">{{ count($transactions) }}</p>
            </div>
        </div>
    </div>

    <div class="bg-[#1f1f1f] p-6 rounded-[2rem] border border-gray-800 mb-8 shadow-xl">
        <h3 class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-4 flex items-center gap-2">
            <i class="fa fa-filter text-[#fbbf24]"></i> Filter & Ekspor Data Transaksi
        </h3>
        
        <form action="{{ route('admin.transactions.export') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div>
                <label class="text-[9px] text-gray-500 uppercase font-black tracking-wider block mb-2">Tanggal Spesifik</label>
                <input type="date" name="filter_tanggal" class="w-full bg-gray-900 border border-gray-700 p-2.5 rounded-xl text-xs text-white outline-none focus:border-[#fbbf24] transition-all font-semibold cursor-pointer">
            </div>

            <div>
                <label class="text-[9px] text-gray-500 uppercase font-black tracking-wider block mb-2">Pilih Bulan</label>
                <select name="filter_bulan" class="w-full bg-gray-900 border border-gray-700 p-2.5 rounded-xl text-xs text-white outline-none focus:border-[#fbbf24] transition-all font-semibold cursor-pointer">
                    <option value="">-- Semua Bulan --</option>
                    <option value="1">Januari</option>
                    <option value="2">Februari</option>
                    <option value="3">Maret</option>
                    <option value="4">April</option>
                    <option value="5">Mei</option>
                    <option value="6">Juni</option>
                    <option value="7">Juli</option>
                    <option value="8">Agustus</option>
                    <option value="9">September</option>
                    <option value="10">Oktober</option>
                    <option value="11">November</option>
                    <option value="12">Desember</option>
                </select>
            </div>

            <div>
                <label class="text-[9px] text-gray-500 uppercase font-black tracking-wider block mb-2">Ketik Tahun</label>
                <input type="number" name="filter_tahun" placeholder="Contoh: 2026" min="2020" max="2030" class="w-full bg-gray-900 border border-gray-700 p-2.5 rounded-xl text-xs text-white outline-none focus:border-[#fbbf24] transition-all font-semibold">
            </div>

            <div>
                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white text-xs font-black py-3 rounded-xl uppercase tracking-widest transition-all shadow-lg active:scale-95 flex items-center justify-center gap-2">
                    <i class="fa fa-file-excel text-sm"></i> Download Excel
                </button>
            </div>
        </form>
    </div>

    <div class="bg-[#1f1f1f] rounded-[2rem] border border-gray-800 overflow-hidden shadow-2xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-black/40 text-[10px] uppercase tracking-[0.2em] text-gray-500">
                        <th class="p-6">Informasi Transaksi</th>
                        <th class="p-6">Detail Pelanggan</th>
                        <th class="p-6">Item & Harga</th>
                        <th class="p-6 text-center">Status</th>
                        <th class="p-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800">
                    @foreach($transactions as $trx)
                    <tr class="hover:bg-white/[0.02] transition-colors">
                        
                        <td class="p-6">
                            <p class="text-xs font-black text-white group-hover:text-[#fbbf24] transition-colors">{{ $trx->no_transaksi }}</p>
                            <p class="text-[10px] text-gray-500 font-mono mt-1 italic">{{ $trx->tanggal }}</p>
                        </td>

                        <td class="p-6">
                            <div class="flex flex-col gap-1">
                                <div class="flex items-center gap-2">
                                    <i class="fa fa-user text-[10px] text-gray-600"></i>
                                    <span class="text-xs font-bold text-gray-300">{{ $trx->id_akun }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <i class="fa fa-envelope text-[10px] text-gray-600"></i>
                                    <span class="text-[10px] text-[#fbbf24] lowercase font-medium">{{ $trx->email }}</span>
                                </div>
                            </div>
                        </td>

                        <td class="p-6">
                            <p class="text-[10px] font-black text-white uppercase italic tracking-wider">{{ $trx->nama_game }}</p>
                            <p class="text-xs font-black text-[#fbbf24] mt-0.5">{{ $trx->nominal }}</p>
                            <p class="text-[9px] text-gray-600 uppercase font-bold mt-1 tracking-tighter">{{ $trx->metode_pembayaran }}</p>
                        </td>

                        <td class="p-6 text-center">
                            <span class="inline-block px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest
                                {{ $trx->status == 'Success' ? 'bg-green-500/10 text-green-500 border border-green-500/20' : 
                                   ($trx->status == 'Failed' ? 'bg-red-500/10 text-red-500 border border-red-500/20' : 
                                   'bg-yellow-500/10 text-[#fbbf24] border border-yellow-500/20') }}">
                                {{ $trx->status }}
                            </span>
                        </td>

                        <td class="p-6">
                            <form action="{{ route('admin.updateStatus', $trx->id_transaksi) }}" method="POST" class="flex items-center justify-center gap-2">
                                @csrf
                                <select name="status" class="bg-gray-900 border border-gray-700 text-[10px] font-black uppercase rounded-lg px-2 py-2 outline-none focus:border-[#fbbf24] transition-all cursor-pointer">
                                    <option value="Pending" {{ $trx->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="Success" {{ $trx->status == 'Success' ? 'selected' : '' }}>Success</option>
                                    <option value="Failed" {{ $trx->status == 'Failed' ? 'selected' : '' }}>Failed</option>
                                </select>
                                <button type="submit" class="bg-[#fbbf24] hover:bg-yellow-500 text-black w-8 h-8 rounded-lg flex items-center justify-center transition-all shadow-lg active:scale-95">
                                    <i class="fa fa-save text-xs"></i>
                                </button>
                            </form>
                        </td>

                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        @if(count($transactions) == 0)
        <div class="p-20 text-center">
            <i class="fa fa-inbox text-4xl text-gray-800 mb-4"></i>
            <p class="text-xs text-gray-600 font-bold uppercase tracking-[0.3em]">Belum ada transaksi masuk</p>
        </div>
        @endif
    </div>
</div>
@endsection