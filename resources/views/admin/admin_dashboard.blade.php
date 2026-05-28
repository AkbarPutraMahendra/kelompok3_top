@extends('layouts.admin')

@section('content')
<div class="min-h-screen text-gray-100 p-1">
    
    <div class="mb-8">
        <h1 class="text-2xl font-black uppercase tracking-wider text-white">
            Ringkasan <span class="text-[#fbbf24]">Bisnis</span>
        </h1>
        <p class="text-xs text-gray-500 font-bold uppercase tracking-widest mt-1">
            Metrik performa operasional K3STORE saat ini
        </p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        
        <div class="bg-[#1f1f1f] border border-gray-800 p-6 rounded-[2rem] relative overflow-hidden shadow-xl flex items-center justify-between">
            <div>
                <p class="text-[10px] font-black uppercase tracking-wider text-gray-500">Total Transaksi</p>
                <h3 class="text-3xl font-black text-white mt-1">{{ $totalTransaksi }}</h3>
            </div>
            <div class="w-12 h-12 bg-blue-500/10 rounded-2xl flex items-center justify-center text-blue-400">
                <i class="fa fa-shopping-cart text-xl"></i>
            </div>
            <div class="absolute -bottom-4 -right-4 w-16 h-16 bg-blue-500/5 rounded-full blur-xl"></div>
        </div>

        <div class="bg-[#1f1f1f] border border-gray-800 p-6 rounded-[2rem] relative overflow-hidden shadow-xl flex items-center justify-between">
            <div>
                <p class="text-[10px] font-black uppercase tracking-wider text-gray-500">Transaksi Sukses</p>
                <h3 class="text-3xl font-black text-green-400 mt-1">{{ $transaksiSukses }}</h3>
            </div>
            <div class="w-12 h-12 bg-green-500/10 rounded-2xl flex items-center justify-center text-green-400">
                <i class="fa fa-check-circle text-xl"></i>
            </div>
            <div class="absolute -bottom-4 -right-4 w-16 h-16 bg-green-500/5 rounded-full blur-xl"></div>
        </div>

        <div class="bg-[#1f1f1f] border border-gray-800 p-6 rounded-[2rem] relative overflow-hidden shadow-xl flex items-center justify-between">
            <div>
                <p class="text-[10px] font-black uppercase tracking-wider text-gray-500">Pesanan Pending</p>
                <h3 class="text-3xl font-black text-yellow-500 mt-1">{{ $transaksiPending }}</h3>
            </div>
            <div class="w-12 h-12 bg-yellow-500/10 rounded-2xl flex items-center justify-center text-yellow-500">
                <i class="fa fa-clock text-xl"></i>
            </div>
            <div class="absolute -bottom-4 -right-4 w-16 h-16 bg-yellow-500/5 rounded-full blur-xl"></div>
        </div>

        <div class="bg-[#1f1f1f] border border-gray-800 p-6 rounded-[2rem] relative overflow-hidden shadow-xl flex items-center justify-between">
            <div>
                <p class="text-[10px] font-black uppercase tracking-wider text-gray-500">Katalog Game</p>
                <h3 class="text-3xl font-black text-white mt-1">{{ $totalGames }}</h3>
            </div>
            <div class="w-12 h-12 bg-purple-500/10 rounded-2xl flex items-center justify-center text-purple-400">
                <i class="fa fa-gamepad text-xl"></i>
            </div>
            <div class="absolute -bottom-4 -right-4 w-16 h-16 bg-purple-500/5 rounded-full blur-xl"></div>
        </div>

    </div>

    <div class="bg-[#1f1f1f] border border-gray-800 rounded-[2.5rem] shadow-2xl p-6 relative overflow-hidden">
        
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <h2 class="text-sm font-black uppercase tracking-wider text-white flex items-center gap-2">
                    <i class="fa fa-history text-xs text-[#fbbf24]"></i> Aktivitas 5 Transaksi Terbaru
                </h2>
                <p class="text-[10px] text-gray-500 font-bold uppercase tracking-wider mt-0.5">
                    Memantau pesanan real-time yang baru masuk
                </p>
            </div>
            <div>
                <a href="{{ route('admin.pesanan.index') }}" class="inline-flex items-center gap-2 bg-gray-800 hover:bg-gray-700 text-white text-[10px] font-black uppercase tracking-widest px-4 py-2.5 rounded-xl transition-all shadow-md active:scale-95">
                    Lihat Semua <i class="fa fa-arrow-right text-[9px] text-[#fbbf24]"></i>
                </a>
            </div>
        </div>

        <div class="overflow-x-auto rounded-2xl border border-gray-800 bg-gray-900/50">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-800 bg-gray-900/80 text-[10px] font-black uppercase tracking-widest text-gray-400">
                        <th class="p-4">No Transaksi</th>
                        <th class="p-4">ID Akun</th>
                        <th class="p-4">Game</th>
                        <th class="p-4">Nominal</th>
                        <th class="p-4 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="text-xs font-semibold text-gray-300 divide-y divide-gray-800/50">
                    @forelse($recentTransactions as $rt)
                    <tr class="hover:bg-gray-800/30 transition-colors">
                        <td class="p-4 font-mono text-white text-[11px] tracking-wide font-bold">
                            {{ $rt->no_transaksi }}
                        </td>
                        <td class="p-4">
                            <span class="bg-gray-800/60 text-gray-300 px-2.5 py-1 rounded-md border border-gray-700 text-[11px]">
                                {{ $rt->id_akun }}
                            </span>
                        </td>
                        <td class="p-4 font-bold text-gray-200">
                            {{ $rt->nama_game }}
                        </td>
                        <td class="p-4 text-[#fbbf24] font-bold">
                            {{ $rt->nominal }}
                        </td>
                        <td class="p-4 text-center">
                            @if($rt->status == 'Success')
                                <span class="inline-block bg-green-500/10 border border-green-500/30 text-green-400 text-[9px] font-black uppercase tracking-widest px-3 py-1 rounded-full">
                                    Success
                                </span>
                            @elseif($rt->status == 'Pending')
                                <span class="inline-block bg-yellow-500/10 border border-yellow-500/30 text-yellow-500 text-[9px] font-black uppercase tracking-widest px-3 py-1 rounded-full animate-pulse">
                                    Pending
                                </span>
                            @else
                                <span class="inline-block bg-red-500/10 border border-red-500/30 text-red-400 text-[9px] font-black uppercase tracking-widest px-3 py-1 rounded-full">
                                    Failed
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-10 text-center text-gray-500 font-bold uppercase tracking-widest text-[10px]">
                            <i class="fa fa-folder-open text-2xl block mb-2 text-gray-700"></i>
                            Belum ada riwayat transaksi saat ini
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>
@endsection