<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Metode Pembayaran | K3 STORE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .bg-dark-primary { background-color: #121212; }
        .bg-dark-secondary { background-color: #1f1f1f; }
        .text-gold { color: #fbbf24; }
        .bg-gold { background-color: #fbbf24; }
    </style>
</head>
<body class="bg-dark-primary text-white font-sans antialiased">

    <nav class="bg-dark-secondary py-4 px-6 flex justify-between items-center border-b border-gray-800 sticky top-0 z-50">
        <div class="flex items-center gap-10">
            <a href="{{ route('home') }}" class="flex items-center gap-2 group transition-all">
                <div class="bg-gold p-2 rounded-lg shadow-[0_0_15px_rgba(251,191,36,0.3)]">
                    <i class="fa fa-bolt text-black text-xl"></i>
                </div>
                <h1 class="text-xl font-black tracking-tighter uppercase italic">
                    K3<span class="text-gold">STORE</span>
                </h1>
            </a>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-10 max-w-4xl">
        
        @if(session('error'))
        <div class="mb-6 p-4 bg-red-500/10 border border-red-500/20 text-red-500 rounded-2xl text-sm font-bold">
            {{ session('error') }}
        </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            
            <div class="md:col-span-1">
                <div class="bg-dark-secondary border border-gray-800 rounded-3xl p-6 sticky top-24 text-center">
                    <img src="{{ asset('images/' . $game->id_game . '.png') }}" 
                         onerror="this.onerror=null; this.src='{{ asset('images/default.png') }}';" 
                         alt="{{ $game->nama_game }}" 
                         class="w-32 h-32 mx-auto rounded-3xl object-cover shadow-xl mb-4 border-2 border-gray-800">
                    <h2 class="text-xl font-black uppercase tracking-wider mb-2">{{ $game->nama_game }}</h2>
                    <p class="text-xs text-gray-400 leading-relaxed">Top up aman, murah, dan instan hanya di K3 STORE. Pilihan pembayaran lengkap termasuk DANA dan QRIS Otomatis.</p>
                </div>
            </div>

            <div class="md:col-span-2">
                <form action="{{ route('topup.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <input type="hidden" name="id_game" value="{{ $game->id_game }}">

                    <div class="bg-dark-secondary border border-gray-800 rounded-3xl p-6">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-6 h-6 rounded-full bg-gold text-black flex items-center justify-center font-black text-xs">1</div>
                            <h3 class="font-black uppercase tracking-wider text-sm">Lengkapi Data Akun</h3>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-2">ID Akun / User ID</label>
                                <input type="text" name="id_akun" required placeholder="Masukkan ID Akun" class="w-full bg-dark-primary border border-gray-800 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-gold transition-colors text-white">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-2">Email (Untuk Nota)</label>
                                <input type="email" name="email" required placeholder="alamat@email.com" class="w-full bg-dark-primary border border-gray-800 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-gold transition-colors text-white">
                            </div>
                        </div>
                    </div>

                    <div class="bg-dark-secondary border border-gray-800 rounded-3xl p-6">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-6 h-6 rounded-full bg-gold text-black flex items-center justify-center font-black text-xs">2</div>
                            <h3 class="font-black uppercase tracking-wider text-sm">Pilih Nominal Top Up</h3>
                        </div>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                            @foreach($nominals as $nominal)
                            <label class="border border-gray-800 rounded-2xl p-4 text-center cursor-pointer hover:border-gold block relative transition-all group">
                                <input type="radio" name="nominal" value="{{ $nominal->layanan }}" required class="absolute top-3 right-3 accent-gold">
                                <p class="font-bold text-xs uppercase tracking-wide mb-1 group-hover:text-gold transition-colors">{{ $nominal->layanan }}</p>
                                <p class="text-[11px] text-gray-400 font-semibold">Rp {{ number_format($nominal->harga, 0, ',', '.') }}</p>
                            </label>
                            @endforeach
                        </div>
                        @if(count($nominals) == 0)
                        <p class="text-xs text-gray-500 italic text-center py-4">Belum ada varian produk untuk game ini.</p>
                        @endif
                    </div>

                    <div class="bg-dark-secondary border border-gray-800 rounded-3xl p-6">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-6 h-6 rounded-full bg-gold text-black flex items-center justify-center font-black text-xs">3</div>
                            <h3 class="font-black uppercase tracking-wider text-sm">Metode Pembayaran</h3>
                        </div>
                        
                        <div class="space-y-3">
                            <label class="flex items-center justify-between p-4 bg-dark-primary border border-gray-800 rounded-2xl cursor-pointer hover:border-gold transition-colors">
                                <div class="flex items-center gap-3">
                                    <input type="radio" name="metode_pembayaran" value="QRIS" checked class="accent-gold">
                                    <span class="text-xs font-bold tracking-wider">QRIS / E-WALLET AUTOMATIC</span>
                                </div>
                                <i class="fa fa-qrcode text-gray-400"></i>
                            </label>
                            
                            <label class="flex items-center justify-between p-4 bg-dark-primary border border-gray-800 rounded-2xl cursor-pointer hover:border-gold transition-colors">
                                <div class="flex items-center gap-3">
                                    <input type="radio" name="metode_pembayaran" value="DANA" class="accent-gold">
                                    <span class="text-xs font-bold tracking-wider">TRANSFER DANA</span>
                                </div>
                                <i class="fa fa-wallet text-gray-400"></i>
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-gold text-black font-black text-xs uppercase tracking-[0.2em] py-4 rounded-2xl hover:bg-yellow-500 transition-colors shadow-[0_4px_20px_rgba(251,191,36,0.2)]">
                        <i class="fa fa-shopping-cart mr-2"></i> Beli Sekarang & Bayar
                    </button>

                </form>
            </div>

        </div>
    </div>

    <footer class="bg-dark-secondary py-8 border-t border-gray-800 mt-20 text-center text-gray-600 text-[10px] font-bold tracking-[0.3em] uppercase">
        &copy; 2026 KELOMPOK 3 ADVERTISING PROJECT
    </footer>

</body>
</html>