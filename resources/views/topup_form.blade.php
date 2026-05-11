<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Topup {{ $game->nama_game }} | K3 STORE</title>
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
                <h1 class="text-xl font-black tracking-tighter uppercase italic group-hover:text-gold transition-colors">K3<span class="text-gold">STORE</span></h1>
            </a>
        </div>
    </nav>

    <main class="container mx-auto px-4 py-12 max-w-5xl">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            
            <div class="md:col-span-1">
                <div class="bg-dark-secondary rounded-[2rem] border border-gray-800 overflow-hidden shadow-2xl">
                    <img src="{{ asset('images/' . $game->id_game . '.png') }}" class="w-full h-64 object-cover">
                    <div class="p-8">
                        <h2 class="text-2xl font-black uppercase italic tracking-tighter mb-4">{{ $game->nama_game }}</h2>
                        <div class="space-y-4 text-gray-400 text-sm leading-relaxed">
                            <p>1. Masukkan Email Anda.</p>
                            <p>2. Masukkan User ID & Server Akun Anda.</p>
                            <p>3. Klik Beli Sekarang untuk memproses pesanan.</p>
                        </div>
                        <div class="mt-8 pt-6 border-t border-gray-800 flex items-center gap-2 text-[10px] font-bold text-gold uppercase tracking-[0.2em]">
                            <i class="fa fa-bolt"></i> Proses Otomatis & Instan
                        </div>
                    </div>
                </div>
            </div>

            <div class="md:col-span-2 space-y-6">
                @if(session('success'))
                    <div class="bg-green-500/10 border border-green-500 text-green-500 p-6 rounded-2xl">
                        <p class="font-black text-sm uppercase tracking-wider mb-1">Berhasil!</p>
                        <p class="text-xs opacity-90">{{ session('success') }}</p>
                    </div>
                @endif

                <div class="bg-dark-secondary p-8 rounded-[2rem] border border-gray-800 shadow-2xl">
                    <div class="flex items-center gap-4 mb-8">
                        <span class="bg-gold text-black w-8 h-8 flex items-center justify-center rounded-full font-black italic text-sm">!</span>
                        <h3 class="text-xl font-black uppercase tracking-widest">Lengkapi Data</h3>
                    </div>

                    <form action="{{ route('topup.store') }}" method="POST" class="space-y-6">
                        @csrf
                        <input type="hidden" name="id_game" value="{{ $game->id_game }}">

                        <div>
                            <label class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-500 ml-2">Email Aktif</label>
                            <input type="email" name="email" class="w-full bg-gray-900 border border-gray-700 p-4 rounded-2xl mt-2 focus:border-gold outline-none transition-all" placeholder="email@contoh.com" required>
                        </div>

                        <div>
                            <label class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-500 ml-2">User ID (Server)</label>
                            <input type="text" name="id_akun" class="w-full bg-gray-900 border border-gray-700 p-4 rounded-2xl mt-2 focus:border-gold outline-none transition-all" placeholder="Contoh: 12345678 (2001)" required>
                        </div>

                        <button type="submit" class="w-full bg-gold hover:bg-yellow-500 text-black font-black py-4 rounded-2xl transition-all shadow-lg uppercase tracking-widest mt-4">
                            Beli Sekarang <i class="fa fa-arrow-right ml-2"></i>
                        </button>
                    </form>
                </div>

                <div class="flex justify-center gap-8 opacity-20">
                    <i class="fa fa-shield-halved text-3xl"></i>
                    <i class="fa fa-lock text-3xl"></i>
                    <i class="fa fa-check-double text-3xl"></i>
                </div>
            </div>
        </div>
    </main>

    <footer class="py-10 text-center opacity-30">
        <p class="text-[10px] font-bold tracking-[0.3em] uppercase italic">K3STORE Digital Delivery System</p>
    </footer>

</body>
</html>