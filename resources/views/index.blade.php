Berikut adalah kode utuh untuk file **`index.blade.php`** kamu yang sudah disesuaikan agar membaca data secara dinamis dari database.

Variabel di dalam `@foreach` telah disesuaikan menggunakan `$games` (sesuai fungsi `index` di controller), dan pemanggilan gambarnya diubah agar fleksibel membaca kolom `$game->gambar` hasil upload dari admin panel.

Silakan **copy** seluruh kode di bawah ini dan langsung **timpa (replace)** semua isi file `resources/views/index.blade.php`:

```html
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>K3 STORE | Pusat Topup Game Terpercaya</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .bg-dark-primary { background-color: #121212; }
        .bg-dark-secondary { background-color: #1f1f1f; }
        .text-gold { color: #fbbf24; }
        .bg-gold { background-color: #fbbf24; }
        
        .game-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .game-card:hover {
            transform: translateY(-8px);
            border-color: #fbbf24;
            box-shadow: 0 10px 25px rgba(251, 191, 36, 0.15);
        }

        /* Hover Effect untuk Logo */
        .logo-home:hover .logo-icon {
            transform: rotate(15deg) scale(1.1);
            box-shadow: 0 0 20px rgba(251, 191, 36, 0.6);
        }
    </style>
</head>
<body class="bg-dark-primary text-white font-sans antialiased">

    <nav class="bg-dark-secondary py-4 px-6 flex justify-between items-center border-b border-gray-800 sticky top-0 z-50">
        <div class="flex items-center gap-10">
            <a href="{{ route('home') }}" class="logo-home flex items-center gap-2 group transition-all">
                <div class="logo-icon bg-gold p-2 rounded-lg shadow-[0_0_15px_rgba(251,191,36,0.3)] transition-all duration-300">
                    <i class="fa fa-bolt text-black text-xl"></i>
                </div>
                <h1 class="text-xl font-black tracking-tighter uppercase italic group-hover:text-gold transition-colors">
                    K3<span class="text-gold group-hover:text-white transition-colors">STORE</span>
                </h1>
            </a>
            
            <div class="flex gap-8 text-[10px] font-black tracking-[0.2em]">
                <a href="{{ route('home') }}" class="text-gold transition-all flex items-center gap-2">
                    <i class="fa fa-gamepad"></i> TOPUP
                </a>
                <a href="{{ route('transaksi.search') }}" class="text-gray-400 hover:text-gold transition-all flex items-center gap-2">
                    <i class="fa fa-search"></i> LACAK PESANAN
                </a>
            </div>
        </div>

        <div class="hidden md:flex items-center gap-2 text-[10px] font-bold text-green-500 uppercase tracking-widest bg-green-500/10 px-3 py-1 rounded-full border border-green-500/20">
            <span class="relative flex h-2 w-2">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
            </span>
            Sistem Online
        </div>
    </nav>

    <header class="container mx-auto px-4 py-10">
        <div class="relative overflow-hidden bg-gradient-to-br from-yellow-900/20 to-transparent p-10 md:p-14 rounded-[2rem] border border-gray-800 shadow-2xl">
            <div class="relative z-10">
                <h2 class="text-4xl md:text-5xl font-black mb-3 uppercase italic tracking-tighter">
                    Layanan <span class="text-gold italic">Top Up</span> Terbaik
                </h2>
                <p class="text-gray-400 text-base max-w-lg leading-relaxed mb-6">
                    Proses instan tanpa ribet. Cukup klik logo <span class="text-gold font-bold">K3 STORE</span> untuk kembali ke katalog utama kapan saja.
                </p>
                <div class="flex items-center gap-6 text-[11px] font-bold text-gray-400 uppercase tracking-widest">
                    <div class="flex items-center gap-2">
                        <i class="fa fa-clock text-gold"></i> Fast Response
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fa fa-shield-halved text-gold"></i> Secure Payment
                    </div>
                </div>
            </div>
            <div class="absolute -right-20 -top-20 w-80 h-80 bg-gold/10 rounded-full blur-[100px]"></div>
        </div>
    </header>

    <section class="container mx-auto px-4 pb-24">
        <div class="flex items-center gap-4 mb-10">
            <h3 class="text-xl font-black uppercase tracking-[0.2em]">Katalog Game</h3>
            <div class="h-[2px] bg-gradient-to-r from-gold/50 to-transparent flex-grow"></div>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
            @foreach($games as $game)
            <a href="{{ route('topup.detail', $game->id_game) }}" class="game-card bg-dark-secondary border border-gray-800 rounded-3xl overflow-hidden shadow-lg block group">
                <div class="relative aspect-[3/4]">
                    <img src="{{ asset('images/' . ($game->gambar ?? 'default.png')) }}" 
                         alt="{{ $game->nama_game }}" 
                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    
                    <div class="absolute inset-0 bg-gradient-to-t from-black via-black/20 to-transparent opacity-80 transition-opacity group-hover:opacity-90"></div>
                    
                    <div class="absolute bottom-0 left-0 right-0 p-6 text-center">
                        <p class="font-black text-sm uppercase tracking-wider group-hover:text-gold transition-colors truncate">{{ $game->nama_game }}</p>
                        <div class="w-8 h-1 bg-gold mx-auto mt-2 rounded-full transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>

        @if(count($games) == 0)
        <div class="p-20 text-center bg-dark-secondary border border-gray-800 rounded-3xl mt-6">
            <i class="fa fa-gamepad text-4xl text-gray-800 mb-4"></i>
            <p class="text-xs text-gray-600 font-bold uppercase tracking-[0.3em]">Belum ada data game di database</p>
        </div>
        @endif
    </section>

    <footer class="bg-dark-secondary py-12 border-t border-gray-800">
        <div class="container mx-auto px-4 text-center">
            <p class="text-gray-600 text-[10px] font-bold tracking-[0.3em] uppercase">
                &copy; 2026 KELOMPOK 3 ADVERTISING PROJECT
            </p>
        </div>
    </footer>

</body>
</html>