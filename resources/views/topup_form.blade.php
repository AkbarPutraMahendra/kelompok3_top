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
        
        /* Indikator terpilih untuk Nominal */
        .selected-card {
            border-color: #fbbf24 !important;
            background-color: rgba(251, 191, 36, 0.1) !important;
            box-shadow: 0 0 15px rgba(251, 191, 36, 0.2);
        }
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
                            <p>1. Lengkapi Data Akun.</p>
                            <p>2. Pilih Nominal & Harga.</p>
                            <p>3. Pilih Metode Pembayaran.</p>
                            <p>4. Klik Beli & Simpan Nota.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="md:col-span-2 space-y-6">
                <form action="{{ route('topup.store') }}" method="POST" id="topupForm">
                    @csrf
                    <input type="hidden" name="id_game" value="{{ $game->id_game }}">
                    <input type="hidden" name="nominal" id="input_nominal" required>

                    <div class="bg-dark-secondary p-8 rounded-[2rem] border border-gray-800 shadow-2xl mb-6">
                        <div class="flex items-center gap-4 mb-8">
                            <span class="bg-gold text-black w-8 h-8 flex items-center justify-center rounded-full font-black italic text-sm">1</span>
                            <h3 class="text-xl font-black uppercase tracking-widest">Data Akun</h3>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <input type="email" name="email" class="w-full bg-gray-900 border border-gray-700 p-4 rounded-2xl focus:border-gold outline-none transition-all" placeholder="Email Aktif" required>
                            <input type="text" name="id_akun" class="w-full bg-gray-900 border border-gray-700 p-4 rounded-2xl focus:border-gold outline-none transition-all" placeholder="ID (Server)" required>
                        </div>
                    </div>

                    <div class="bg-dark-secondary p-8 rounded-[2rem] border border-gray-800 shadow-2xl mb-6">
                        <div class="flex items-center gap-4 mb-8">
                            <span class="bg-gold text-black w-8 h-8 flex items-center justify-center rounded-full font-black italic text-sm">2</span>
                            <h3 class="text-xl font-black uppercase tracking-widest">Pilih Nominal</h3>
                        </div>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                            @php
                                $gn = strtolower($game->nama_game);
                                if(str_contains($gn, 'mobile legends')) {
                                    $vars = [['l'=>'86 Diamonds', 'h'=>'Rp 22.000'], ['l'=>'172 Diamonds', 'h'=>'Rp 44.000'], ['l'=>'257 Diamonds', 'h'=>'Rp 66.000'], ['l'=>'706 Diamonds', 'h'=>'Rp 175.000']];
                                } elseif(str_contains($gn, 'free fire')) {
                                    $vars = [['l'=>'140 Diamonds', 'h'=>'Rp 19.500'], ['l'=>'355 Diamonds', 'h'=>'Rp 48.000'], ['l'=>'720 Diamonds', 'h'=>'Rp 95.000'], ['l'=>'1440 Diamonds', 'h'=>'Rp 185.000']];
                                } elseif(str_contains($gn, 'roblox')) {
                                    $vars = [['l'=>'80 Robux', 'h'=>'Rp 15.000'], ['l'=>'400 Robux', 'h'=>'Rp 75.000'], ['l'=>'800 Robux', 'h'=>'Rp 145.000'], ['l'=>'1700 Robux', 'h'=>'Rp 310.000']];
                                } else {
                                    $vars = [['l'=>'100 Points', 'h'=>'Rp 15.000'], ['l'=>'500 Points', 'h'=>'Rp 70.000'], ['l'=>'1000 Points', 'h'=>'Rp 135.000']];
                                }
                            @endphp
                            
                            @foreach($vars as $v)
                            <div onclick="setNominal(this, '{{ $v['l'] }}')" class="nominal-item bg-gray-900 border border-gray-700 p-4 rounded-2xl cursor-pointer hover:border-gold transition-all text-center group">
                                <p class="text-xs font-bold group-hover:text-gold transition-colors">{{ $v['l'] }}</p>
                                <p class="text-[9px] text-gray-500 mt-1 font-mono uppercase">{{ $v['h'] }}</p>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="bg-dark-secondary p-8 rounded-[2rem] border border-gray-800 shadow-2xl mb-6">
                        <div class="flex items-center gap-4 mb-8">
                            <span class="bg-gold text-black w-8 h-8 flex items-center justify-center rounded-full font-black italic text-sm">3</span>
                            <h3 class="text-xl font-black uppercase tracking-widest">Metode Pembayaran</h3>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            
                            <label class="group relative cursor-pointer">
                                <input type="radio" name="metode_pembayaran" value="QRIS" class="peer hidden" required>
                                <div class="flex items-center justify-between p-4 bg-gray-900 border border-gray-700 rounded-2xl transition-all peer-checked:border-gold peer-checked:bg-gold/10 group-hover:border-gold/50">
                                    <span class="text-xs font-bold italic uppercase tracking-wider peer-checked:text-gold">QRIS (Otomatis)</span>
                                    <i class="fa fa-qrcode text-gray-600 peer-checked:text-gold"></i>
                                </div>
                                <div class="absolute -top-2 -right-2 bg-gold text-black w-5 h-5 rounded-full flex items-center justify-center scale-0 peer-checked:scale-100 transition-transform">
                                    <i class="fa fa-check text-[10px] font-bold"></i>
                                </div>
                            </label>

                            <label class="group relative cursor-pointer">
                                <input type="radio" name="metode_pembayaran" value="DANA" class="peer hidden">
                                <div class="flex items-center justify-between p-4 bg-gray-900 border border-gray-700 rounded-2xl transition-all peer-checked:border-gold peer-checked:bg-gold/10 group-hover:border-gold/50">
                                    <span class="text-xs font-bold italic uppercase tracking-wider peer-checked:text-gold">DANA</span>
                                    <i class="fa fa-wallet text-gray-600 peer-checked:text-gold"></i>
                                </div>
                                <div class="absolute -top-2 -right-2 bg-gold text-black w-5 h-5 rounded-full flex items-center justify-center scale-0 peer-checked:scale-100 transition-transform">
                                    <i class="fa fa-check text-[10px] font-bold"></i>
                                </div>
                            </label>

                        </div>
                    </div>

                    <button type="submit" class="w-full bg-gold hover:bg-yellow-500 text-black font-black py-5 rounded-2xl transition-all shadow-lg uppercase tracking-widest text-sm">
                        Beli Sekarang <i class="fa fa-bolt ml-2"></i>
                    </button>
                </form>
            </div>
        </div>
    </main>

    <footer class="py-10 text-center opacity-30">
        <p class="text-[10px] font-bold tracking-[0.3em] uppercase italic">K3STORE Digital Delivery System</p>
    </footer>

    <script>
        function setNominal(element, value) {
            document.querySelectorAll('.nominal-item').forEach(item => {
                item.classList.remove('selected-card');
            });
            element.classList.add('selected-card');
            document.getElementById('input_nominal').value = value;
        }
    </script>

</body>
</html>