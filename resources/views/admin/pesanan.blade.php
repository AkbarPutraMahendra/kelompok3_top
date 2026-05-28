@extends('layouts.admin')

@section('content')
<div class="min-h-screen text-gray-100 p-1">
    
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-black uppercase tracking-wider text-white">
                Kelola <span class="text-[#fbbf24]">Pesanan</span>
            </h1>
            <p class="text-xs text-gray-500 font-bold uppercase tracking-widest mt-1">
                Daftar seluruh riwayat transaksi masuk di K3STORE
            </p>
        </div>
        
        <div>
            <button onclick="if(confirm('Apakah Anda yakin ingin MENGHAPUS SEMUA transaksi? Tindakan ini tidak bisa dibatalkan!')) { document.getElementById('truncate-form').submit(); }" class="bg-red-500/10 hover:bg-red-600 text-red-400 hover:text-white text-[10px] font-black uppercase tracking-widest px-4 py-2.5 rounded-xl transition-all shadow-md active:scale-95">
                <i class="fa fa-trash-alt mr-1"></i> Bersihkan Log
            </button>
            <form id="truncate-form" action="{{ route('admin.transactions.truncate') }}" method="POST" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>

    <div class="bg-[#1f1f1f] border border-gray-800 rounded-3xl p-5 mb-6 shadow-xl">
        <form action="{{ route('admin.pesanan.index') }}" method="GET" id="filter-form">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                
                <div class="flex flex-col gap-1.5">
                    <label class="text-[10px] font-black uppercase tracking-wider text-gray-400">Cari Transaksi</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="No TRX / ID Akun / Email" 
                        class="w-full bg-gray-900 border border-gray-800 rounded-xl px-3 py-2 text-xs font-semibold text-gray-300 placeholder-gray-600 focus:outline-none focus:border-[#fbbf24]">
                </div>

                <div class="flex flex-col gap-1.5">
                    <label class="text-[10px] font-black uppercase tracking-wider text-gray-400">Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="{{ request('tanggal_mulai') }}"
                        class="w-full bg-gray-900 border border-gray-800 rounded-xl px-3 py-2 text-xs font-semibold text-gray-300 focus:outline-none focus:border-[#fbbf24] {{ request('download_semua') ? 'opacity-40 pointer-events-none' : '' }}">
                </div>

                <div class="flex flex-col gap-1.5">
                    <label class="text-[10px] font-black uppercase tracking-wider text-gray-400">Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai" id="tanggal_selesai" value="{{ request('tanggal_selesai') }}"
                        class="w-full bg-gray-900 border border-gray-800 rounded-xl px-3 py-2 text-xs font-semibold text-gray-300 focus:outline-none focus:border-[#fbbf24] {{ request('download_semua') ? 'opacity-40 pointer-events-none' : '' }}">
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="flex-1 bg-[#fbbf24] hover:bg-[#d9a41b] text-gray-900 text-[11px] font-black uppercase tracking-wider py-2.5 rounded-xl transition-all text-center active:scale-95 shadow-md">
                        <i class="fa fa-filter mr-1"></i> Filter
                    </button>
                    
                    <a href="{{ route('admin.pesanan.index') }}" class="bg-gray-800 hover:bg-gray-700 text-gray-300 text-[11px] font-black uppercase tracking-wider py-2.5 px-4 rounded-xl transition-all text-center active:scale-95 border border-gray-700">
                        Reset
                    </a>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between border-t border-gray-800/60 mt-4 pt-4 gap-4">
                <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                    <input type="checkbox" name="download_semua" id="download_semua" value="1" onchange="toggleTanggal(this)" {{ request('download_semua') ? 'checked' : '' }}
                        class="w-4 h-4 rounded text-[#fbbf24] bg-gray-900 border-gray-700 focus:ring-0 focus:ring-offset-0 cursor-pointer">
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wide">Download Semua Data (Awal s/d Akhir)</span>
                </label>

                <button type="submit" formmethod="GET" formaction="{{ route('admin.transactions.export') }}" 
                    class="bg-emerald-500/10 hover:bg-emerald-600 text-emerald-400 hover:text-white text-[10px] font-black uppercase tracking-widest px-5 py-2.5 rounded-xl transition-all shadow-md active:scale-95 inline-flex items-center gap-1.5">
                    <i class="fa fa-file-excel text-sm"></i> Download Excel
                </button>
            </div>
        </form>
    </div>

    <div class="bg-[#1f1f1f] border border-gray-800 rounded-[2.5rem] shadow-2xl p-6 relative overflow-hidden">
        <div class="overflow-x-auto rounded-2xl border border-gray-800 bg-gray-900/50">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-800 bg-gray-900/80 text-[10px] font-black uppercase tracking-widest text-gray-400">
                        <th class="p-4">No Transaksi</th>
                        <th class="p-4">Pelanggan (Email)</th>
                        <th class="p-4">ID Akun Game</th>
                        <th class="p-4">Game</th>
                        <th class="p-4">Nominal</th>
                        <th class="p-4">Metode</th>
                        <th class="p-4 text-center" style="width: 180px;">Status / Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-xs font-semibold text-gray-300 divide-y divide-gray-800/50">
                    @forelse($transactions as $t)
                    <tr class="hover:bg-gray-800/30 transition-colors">
                        <td class="p-4 font-mono text-white text-[11px] tracking-wide font-bold">
                            {{ $t->no_transaksi }}
                        </td>
                        <td class="p-4 text-gray-400 font-medium">
                            {{ $t->email }}
                        </td>
                        <td class="p-4">
                            <span class="bg-gray-800/60 text-gray-300 px-2.5 py-1 rounded-md border border-gray-700 text-[11px]">
                                {{ $t->id_akun }}
                            </span>
                        </td>
                        <td class="p-4 font-bold text-gray-200">
                            {{ $t->nama_game }}
                        </td>
                        <td class="p-4 text-[#fbbf24] font-bold">
                            {{ $t->nominal }}
                        </td>
                        <td class="p-4 text-gray-400 text-[11px]">
                            {{ strtoupper($t->metode_pembayaran) }}
                        </td>
                        <td class="p-4 text-center">
                            <form action="{{ route('admin.updateStatus', $t->id_transaksi) }}" method="POST" class="inline-block w-full">
                                @csrf
                                <select name="status" onchange="this.form.submit()" 
                                    class="w-full text-center text-[10px] font-black uppercase tracking-wider px-3 py-1.5 rounded-full border bg-transparent cursor-pointer transition-all focus:outline-none focus:ring-2 focus:ring-[#fbbf24]/50
                                    @if($t->status == 'Success')
                                        border-green-500/30 text-green-400 bg-green-500/10
                                    @elseif($t->status == 'Pending')
                                        border-yellow-500/30 text-yellow-500 bg-yellow-500/10 animate-pulse
                                    @else
                                        border-red-500/30 text-red-400 bg-red-500/10
                                    @endif">
                                    <option value="Pending" class="bg-[#1f1f1f] text-yellow-500" {{ $t->status == 'Pending' ? 'selected' : '' }}>⏳ Pending</option>
                                    <option value="Success" class="bg-[#1f1f1f] text-green-400" {{ $t->status == 'Success' ? 'selected' : '' }}>✅ Success</option>
                                    <option value="Failed" class="bg-[#1f1f1f] text-red-400" {{ $t->status == 'Failed' ? 'selected' : '' }}>❌ Failed</option>
                                </select>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="p-10 text-center text-gray-500 font-bold uppercase tracking-widest text-[10px]">
                            <i class="fa fa-folder-open text-2xl block mb-2 text-gray-700"></i>
                            Tidak ada transaksi yang cocok atau ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function toggleTanggal(checkbox) {
    const tglMulai = document.getElementById('tanggal_mulai');
    const tglSelesai = document.getElementById('tanggal_selesai');
    
    if (checkbox.checked) {
        tglMulai.classList.add('opacity-40', 'pointer-events-none');
        tglSelesai.classList.add('opacity-40', 'pointer-events-none');
        tglMulai.value = '';
        tglSelesai.value = '';
    } else {
        tglMulai.classList.remove('opacity-40', 'pointer-events-none');
        tglSelesai.classList.remove('opacity-40', 'pointer-events-none');
    }
}
</script>
@endsection