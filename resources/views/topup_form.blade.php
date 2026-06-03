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
                    <h2 class="text-xl font-black uppercase tracking-wider mb-2" id="current-game-name">{{ $game->nama_game }}</h2>
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
                        
                        <div class="space-y-4">
                            {{-- PERBAIKAN UTAMA PHP: Sekarang memeriksa isi database tipe_form, bukan nama teks game lagi --}}
                            @if(($game->tipe_form ?? 'single') === 'double')
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-2">User ID</label>
                                    <input type="text" id="user_id" name="user_id" required placeholder="Contoh: 136407462" class="w-full bg-dark-primary border border-gray-800 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-gold transition-colors text-white">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-2">Zone ID / Server</label>
                                    <input type="text" id="zone_id" name="zone_id" required placeholder="Contoh: 15595" class="w-full bg-dark-primary border border-gray-800 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-gold transition-colors text-white">
                                </div>
                            </div>
                            @else
                            <div>
                                <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-2">ID Akun / User ID</label>
                                <input type="text" id="user_id" name="user_id" required placeholder="Masukkan ID Player" class="w-full bg-dark-primary border border-gray-800 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-gold transition-colors text-white">
                                <input type="hidden" id="zone_id" name="zone_id" value="">
                            </div>
                            @endif

                            <div>
                                <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-2">Email (Untuk Nota)</label>
                                <input type="email" name="email" required placeholder="alamat@email.com" class="w-full bg-dark-primary border border-gray-800 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-gold transition-colors text-white">
                            </div>
                        </div>

                        <div id="username-container" class="mt-4 hidden">
                            <div class="bg-green-500/10 border border-green-500/20 text-green-400 text-xs p-3.5 rounded-xl flex items-center gap-2">
                                <i class="fa fa-check-circle text-sm"></i>
                                <span>Nama Akun: <strong id="account-username" class="text-white uppercase font-black tracking-wide">...</strong></span>
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

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        // PERBAIKAN UTAMA JAVASCRIPT: Membaca status tipe form langsung dari data backend PHP
        const tipeForm = "{{ $game->tipe_form ?? 'single' }}";
        const userIdInput = document.getElementById('user_id');
        const zoneIdInput = document.getElementById('zone_id');
        const usernameContainer = document.getElementById('username-container');
        const accountUsername = document.getElementById('account-username');

        // Fitur validasi akun otomatis berjalan HANYA JIKA tipe form di database diset sebagai 'double'
        const isDoubleInput = (tipeForm === 'double');

        function periksaAkun() {
            if (!isDoubleInput) return; 

            const userId = userIdInput.value.trim();
            const zoneId = zoneIdInput.value.trim();

            if (userId.length >= 5 && zoneId.length >= 4) {
                usernameContainer.classList.remove('hidden');
                accountUsername.innerText = 'Memeriksa Akun...';
                accountUsername.className = "text-yellow-400 font-bold animate-pulse";

                fetch("/api/check-game-account", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({ 
                        user_id: userId, 
                        zone_id: zoneId 
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Rute tidak ditemukan atau masalah internal server');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        accountUsername.innerText = data.username;
                        accountUsername.className = "text-white font-black uppercase tracking-wide";
                    } else {
                        accountUsername.innerText = "Akun tidak ditemukan / salah server";
                        accountUsername.className = "text-red-500 font-bold";
                    }
                })
                .catch(error => {
                    accountUsername.innerText = "Fitur cek akun sedang dinonaktifkan";
                    accountUsername.className = "text-red-500 font-bold";
                });
            } else {
                usernameContainer.classList.add('hidden');
            }
        }

        if (userIdInput && zoneIdInput) {
            userIdInput.addEventListener('keyup', periksaAkun);
            zoneIdInput.addEventListener('keyup', periksaAkun);
        }
    });
    </script>
</body>
</html>