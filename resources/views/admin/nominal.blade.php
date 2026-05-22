<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Varian Nominal | K3 STORE Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .bg-dark-primary { background-color: #121212; }
        .bg-dark-secondary { background-color: #1f1f1f; }
        .text-gold { color: #fbbf24; }
        .bg-gold { background-color: #fbbf24; }
    </style>
</head>
<body class="bg-dark-primary text-white font-sans antialiased flex flex-col min-h-screen">

    <nav class="bg-dark-secondary py-4 px-6 flex justify-between items-center border-b border-gray-800 sticky top-0 z-50">
        <div class="flex items-center gap-10">
            <a href="{{ route('home') }}" class="flex items-center gap-2 group transition-all">
                <div class="bg-gold p-2 rounded-lg shadow-[0_0_15px_rgba(251,191,36,0.3)]">
                    <i class="fa fa-bolt text-black text-xl"></i>
                </div>
                <h1 class="text-xl font-black tracking-tighter uppercase italic">
                    K3<span class="text-gold">STORE</span> <span class="text-xs text-gray-400 not-italic font-bold tracking-widest ml-2 border-l border-gray-700 pl-2">ADMIN PANEL</span>
                </h1>
            </a>
        </div>
    </nav>

    <div class="flex flex-1">
        
        <aside class="w-64 bg-dark-secondary border-r border-gray-800 p-6 space-y-2 hidden md:block shrink-0">
            <p class="text-[10px] font-black uppercase tracking-widest text-gray-500 px-3 mb-4">Menu Utama</p>
            <a href="{{ url('/admin/dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-bold uppercase tracking-wider text-gray-400 hover:bg-white/[0.02] hover:text-white transition-all">
                <i class="fa fa-shopping-cart w-5 text-center"></i> Pesanan / Transaksi
            </a>
            <a href="{{ url('/admin/games') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-bold uppercase tracking-wider text-gray-400 hover:bg-white/[0.02] hover:text-white transition-all">
                <i class="fa fa-gamepad w-5 text-center"></i> Kelola Game
            </a>
            <a href="{{ url('/admin/nominal') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-bold uppercase tracking-wider bg-gold text-black shadow-[0_4px_15px_rgba(251,191,36,0.15)] transition-all">
                <i class="fa fa-tags w-5 text-center"></i> Kelola Nominal
            </a>
            <a href="{{ url('/admin/pengaturan') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-bold uppercase tracking-wider text-gray-400 hover:bg-white/[0.02] hover:text-white transition-all">
                <i class="fa fa-qrcode w-5 text-center"></i> Pengaturan QRIS
            </a>
        </aside>

        <main class="flex-1 p-6 md:p-10 max-w-7xl mx-auto w-full">
            
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
                    <a href="{{ route('admin.nominal') }}" class="p-3 bg-dark-secondary border border-gray-800 rounded-xl text-gray-400 hover:text-white transition-colors" title="Kembali pilih game">
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
                                <input type="text" name="layanan" required placeholder="Contoh: 50 Diamonds / 100 Cash" class="w-full bg-dark-primary border border-gray-800 rounded-xl px-4 py-3 text-xs focus:outline-none focus:border-gold transition-colors text-white">
                            </div>

                            <div>
                                <label class="block text-[10px] font-black uppercase tracking-wider text-gray-400 mb-2">Harga Jual (Angka Saja)</label>
                                <input type="number" name="harga" required placeholder="Contoh: 15000" class="w-full bg-dark-primary border border-gray-800 rounded-xl px-4 py-3 text-xs focus:outline-none focus:border-gold transition-colors text-white">
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
                                            <button class="p-2 rounded-lg bg-gray-800/50 border border-gray-700/50 hover:border-red-500 hover:text-red-400 transition-all text-gray-400 text-xs" title="Hapus">
                                                <i class="fa fa-trash"></i>
                                            </button>
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

        </main>
    </div>

</body>
</html>