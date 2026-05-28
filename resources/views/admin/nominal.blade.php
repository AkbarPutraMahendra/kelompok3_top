@extends('layouts.admin')

@section('content')
<div class="p-6 md:p-10 max-w-7xl mx-auto w-full">
    
    @if(session('success'))
    <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 rounded-2xl text-sm font-bold flex items-center gap-2">
        <i class="fa fa-check-circle"></i> {{ session('success') }}
    </div>
    @endif

    @if(!$selectedGame)
    <div class="mb-8">
        <h2 class="text-2xl font-black uppercase tracking-wider">Pilih Game Terlebih Dahulu</h2>
        <p class="text-xs text-gray-400 mt-1">Klik salah satu game di bawah untuk mengelola atau menambah varian nominal top-up didalamnya.</p>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-6">
        @foreach($games as $g)
        <a href="{{ route('admin.nominal.index', ['game_id' => $g->id_game]) }}" class="bg-dark-secondary border border-gray-800 rounded-3xl p-5 text-center hover:border-gold transition-all duration-300 group hover:-translate-y-1 block shadow-lg">
            <img src="{{ asset('images/' . $g->id_game . '.png') }}" 
                 onerror="this.onerror=null; this.src='{{ asset('images/default.png') }}';" 
                 alt="{{ $g->nama_game }}" 
                 class="w-24 h-24 mx-auto rounded-2xl object-cover mb-4 border border-gray-700 group-hover:scale-105 transition-transform">
            <h3 class="font-black uppercase tracking-wide text-xs text-gray-200 group-hover:text-gold transition-colors">{{ $g->nama_game }}</h3>
            <p class="text-[10px] text-gray-500 font-bold tracking-wider mt-2 uppercase">Klik untuk Kelola &rarr;</p>
        </a>
        @endforeach
    </div>

    @else
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.nominal.index') }}" class="p-3 bg-dark-secondary border border-gray-800 rounded-xl text-gray-400 hover:text-white transition-colors" title="Kembali pilih game">
                <i class="fa fa-arrow-left"></i>
            </a>
            <div>
                <div class="flex items-center gap-2">
                    <h2 class="text-2xl font-black uppercase tracking-wider">Nominal: {{ $selectedGame->nama_game }}</h2>
                </div>
                <p class="text-xs text-gray-400 mt-1">Mengatur varian harga dan diamond/koin untuk game {{ $selectedGame->nama_game }}.</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-1">
            <div class="bg-dark-secondary border border-gray-800 rounded-3xl p-6 sticky top-24">
                <h3 class="font-black uppercase text-sm tracking-wider mb-4 text-gold flex items-center gap-2">
                    <i class="fa fa-plus-circle"></i> Tambah Nominal Baru
                </h3>
                
                <form action="{{ route('admin.nominal.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="id_game" value="{{ $selectedGame->id_game }}">

                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-wider text-gray-400 mb-2">Nama Layanan / Varian Item</label>
                        <input type="text" name="layanan" required placeholder="Contoh: 50 Diamonds / 100 Cash" class="w-full bg-black border border-gray-800 rounded-xl px-4 py-3 text-xs focus:outline-none focus:border-gold transition-colors text-white placeholder-gray-600">
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-wider text-gray-400 mb-2">Harga Jual (Angka Saja)</label>
                        <input type="number" name="harga" required placeholder="Contoh: 15000" class="w-full bg-black border border-gray-800 rounded-xl px-4 py-3 text-xs focus:outline-none focus:border-gold transition-colors text-white placeholder-gray-600">
                    </div>

                    <button type="submit" class="w-full bg-gold hover:bg-yellow-500 text-black font-black text-xs uppercase tracking-widest py-3.5 rounded-xl transition-all shadow-[0_4px_15px_rgba(251,191,36,0.2)]">
                        <i class="fa fa-save mr-1"></i> Simpan Nominal
                    </button>
                </form>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="bg-dark-secondary border border-gray-800 rounded-3xl overflow-hidden shadow-2xl">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-800 bg-black/30">
                            <th class="p-4 text-[11px] font-black uppercase tracking-wider text-gray-400 w-12 text-center">No</th>
                            <th class="p-4 text-[11px] font-black uppercase tracking-wider text-gray-400">Layanan / Item Varian</th>
                            <th class="p-4 text-[11px] font-black uppercase tracking-wider text-gray-400">Harga Jual</th>
                            <th class="p-4 text-[11px] font-black uppercase tracking-wider text-gray-400 text-center w-28">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800/50">
                        @forelse($nominals as $nominal)
                        <tr class="hover:bg-white/[0.01] transition-colors group">
                            <td class="p-4 text-xs font-mono text-gray-500 text-center">{{ $loop->iteration }}</td>
                            <td class="p-4">
                                <span class="bg-dark-primary border border-gray-800 px-3 py-1.5 rounded-xl text-xs font-bold text-gray-200">
                                    {{ $nominal->layanan }}
                                </span>
                            </td>
                            <td class="p-4 font-bold text-xs text-yellow-500">
                                Rp {{ number_format($nominal->harga, 0, ',', '.') }}
                            </td>
                            <td class="p-4 text-center">
                                <div class="flex justify-center gap-1.5">
                                    <form action="{{ route('admin.nominal.destroy', $nominal->id_nominal) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus nominal ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 rounded-lg bg-gray-800/50 border border-gray-700/50 hover:border-red-500 hover:text-red-400 transition-all text-gray-400 text-xs" title="Hapus">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="p-8 text-center text-xs text-gray-500 italic">
                                Belum ada varian nominal untuk game ini. Silakan tambahkan lewat form di kiri.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

</div>
@endsection