@extends('layouts.admin')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="flex justify-between items-end mb-8">
        <div>
            <h2 class="text-2xl font-black uppercase italic tracking-widest text-[#fbbf24]">Kelola Pesanan</h2>
            <p class="text-xs text-gray-500 mt-1 uppercase tracking-wider">Daftar transaksi masuk sistem K3STORE</p>
        </div>
        <div class="text-right">
            <p class="text-[10px] text-gray-500 uppercase font-bold">Total Transaksi</p>
            <p class="text-2xl font-black text-[#fbbf24]">{{ count($transactions) }}</p>
        </div>
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