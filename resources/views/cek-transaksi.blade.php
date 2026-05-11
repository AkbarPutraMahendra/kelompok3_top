<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lacak Pesanan | K3 STORE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .bg-dark-primary { background-color: #121212; }
        .bg-dark-secondary { background-color: #1f1f1f; }
        .text-gold { color: #fbbf24; }
        .bg-gold { background-color: #fbbf24; }
        
        .status-badge-pending { @apply bg-yellow-500/10 text-yellow-500 border border-yellow-500/20; }
        .status-badge-success { @apply bg-green-500/10 text-green-500 border border-green-500/20; }
    </style>
</head>
<body class="bg-dark-primary text-white font-sans antialiased">

    <nav class="bg-dark-secondary py-4 px-6 flex justify-between items-center border-b border-gray-800 sticky top-0 z-50">
        <div class="flex items-center gap-10">
            <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                <div class="bg-gold p-2 rounded-lg shadow-[0_0_15px_rgba(251,191,36,0.3)]">
                    <i class="fa fa-bolt text-black text-xl"></i>
                </div>
                <h1 class="text-xl font-black tracking-tighter uppercase italic group-hover:text-gold transition-colors">K3<span class="text-gold">STORE</span></h1>
            </a>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-16 max-w-3xl">
        <div class="text-center mb-10">
            <h2 class="text-3xl font-black uppercase italic tracking-tighter mb-3">Lacak <span class="text-gold">Pesanan</span></h2>
            <p class="text-gray-400">Masukkan Nomor Transaksi atau ID Akun untuk mengecek status pesanan Anda.</p>
        </div>

        <div class="bg-dark-secondary p-8 rounded-[2rem] border border-gray-800 shadow-2xl mb-10">
            <form action="{{ route('transaksi.search') }}" method="GET" class="space-y-6">
                <div>
                    <label class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-500 ml-2">Nomor Transaksi / ID Akun</label>
                    <div class="relative mt-2">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-500">
                            <i class="fa fa-search"></i>
                        </span>
                        <input type="text" name="search" placeholder="Contoh: TRX-2026..." 
                               class="w-full bg-gray-900 border border-gray-700 p-4 pl-12 rounded-2xl focus:border-gold outline-none transition-all text-sm font-bold tracking-widest"
                               value="{{ request('search') }}" required>
                    </div>
                </div>
                <button type="submit" class="w-full bg-gold hover:bg-yellow-500 text-black font-black py-4 rounded-2xl transition-all shadow-lg uppercase tracking-widest">
                    Cek Status Sekarang
                </button>
            </form>
        </div>

        @if(isset($results))
            <div class="space-y-4">
                @forelse($results as $data)
                    <div class="bg-dark-secondary border border-gray-800 p-6 rounded-2xl flex flex-col md:flex-row justify-between items-center gap-4">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-gray-800 rounded-xl flex items-center justify-center text-gold">
                                <i class="fa fa-gamepad text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-black text-sm uppercase tracking-wider">{{ $data->nama_game }}</h4>
                                <p class="text-xs text-gray-500">ID: {{ $data->id_akun }} | <span class="text-gray-400 font-mono">{{ $data->no_transaksi }}</span></p>
                            </div>
                        </div>
                        
                        <div class="flex flex-col items-end">
                            <span class="px-4 py-1 rounded-full text-[10px] font-black uppercase tracking-widest {{ $data->status == 'Sukses' ? 'bg-green-500/10 text-green-500 border border-green-500/20' : 'bg-yellow-500/10 text-yellow-500 border border-yellow-500/20' }}">
                                {{ $data->status }}
                            </span>
                            <span class="text-[9px] text-gray-600 mt-2 font-bold">{{ \Carbon\Carbon::parse($data->tanggal)->format('d M Y, H:i') }}</span>
                        </div>
                    </div>
                @empty
                    <div class="text-center p-10 bg-red-500/5 border border-red-500/20 rounded-2xl">
                        <i class="fa fa-exclamation-triangle text-red-500 text-2xl mb-3"></i>
                        <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">Data tidak ditemukan</p>
                    </div>
                @endforelse
            </div>
        @endif
    </div>

    <footer class="py-10 text-center opacity-30">
        <p class="text-[10px] font-bold tracking-[0.3em] uppercase italic">K3STORE Digital Delivery System</p>
    </footer>

</body>
</html>