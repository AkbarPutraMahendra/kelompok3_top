<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Transaksi #{{ $nota->no_transaksi }} | K3 STORE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .bg-dark-primary { background-color: #121212; }
        .bg-dark-secondary { background-color: #1f1f1f; }
        .text-gold { color: #fbbf24; }
        .bg-gold { background-color: #fbbf24; }
    </style>
</head>
<body class="bg-dark-primary text-white min-h-screen font-sans antialiased">

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

    <main class="container mx-auto px-4 py-12 max-w-md">
        
        <div class="bg-dark-secondary border border-gray-800 p-6 rounded-[2rem] text-center mb-6">
            <div class="flex justify-center items-center gap-3 mb-4">
                <span class="relative flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-yellow-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-yellow-500"></span>
                </span>
                <p class="text-xs font-black uppercase tracking-[0.2em] text-gold">Status: {{ $nota->status }}</p>
            </div>
            <p class="text-[10px] text-gray-500 leading-relaxed uppercase">Pesanan Anda akan diproses otomatis setelah pembayaran dikonfirmasi oleh admin.</p>
        </div>

        <div class="bg-dark-secondary border border-gray-800 p-8 rounded-[2rem] shadow-2xl border-t-4 border-t-gold">
            <h2 class="text-center text-lg font-black uppercase italic mb-6">Pembayaran <span class="text-gold">{{ $nota->metode_pembayaran }}</span></h2>
            
            @if($nota->metode_pembayaran == 'QRIS')
                <div class="bg-white p-4 rounded-2xl mb-6 mx-auto w-48 h-48 flex items-center justify-center">
                    <img src="{{ asset('images/' . $config->qris_path) }}" 
                         onerror="this.onerror=null; this.src='{{ asset('images/qris.png') }}';" 
                         alt="QRIS K3STORE" 
                         class="w-full h-full object-contain">
                </div>
                <p class="text-[10px] text-yellow-500 text-center font-medium mb-6 uppercase">Silakan screenshot / scan kode QRIS di atas</p>
            @else
                <div class="bg-gray-900 border border-dashed border-gray-700 p-6 rounded-2xl mb-6 text-center">
                    <p class="text-[10px] text-gray-500 uppercase mb-2">Transfer DANA ke:</p>
                    <p class="text-xl font-black text-gold tracking-wider">{{ $config->no_dana }}</p>
                    <p class="text-[10px] font-bold mt-1 uppercase text-gray-400">A/N K3 STORE OFFICIAL</p>
                </div>
            @endif

            <div class="bg-gray-900/50 p-4 rounded-2xl border border-gray-800">
                <p class="text-[10px] text-gray-500 uppercase mb-1 text-center">Total Tagihan</p>
                <p class="text-2xl font-black text-center text-gold">
                    Rp {{ number_format($nota->harga ?? 0, 0, ',', '.') }}
                </p>
            </div>

            <div class="mt-8 space-y-3 font-mono text-[11px] border-t border-gray-800 pt-6">
                <div class="flex justify-between">
                    <span class="text-gray-500 uppercase">Nomor TRX</span>
                    <span class="font-bold">{{ $nota->no_transaksi }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500 uppercase">Game</span>
                    <span class="text-right font-bold uppercase">{{ $nota->nama_game }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500 uppercase">Varian</span>
                    <span class="font-bold text-gold uppercase">{{ $nota->nominal }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500 uppercase">Target ID</span>
                    <span class="font-bold">{{ $nota->id_akun }}</span>
                </div>
            </div>

            <div class="mt-8 space-y-3">
                <button onclick="window.print()" class="w-full bg-white/5 hover:bg-white/10 text-white py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all">
                    <i class="fa fa-download mr-2"></i> Simpan Nota (PDF)
                </button>
                <a href="https://wa.me/6282311283934?text=Halo%20Admin%20K3STORE,%20saya%20ingin%20konfirmasi%20pembayaran%20via%20{{ $nota->metode_pembayaran }}%20untuk%20No.%20Transaksi:%20{{ $nota->no_transaksi }}" 
                   target="_blank" 
                   class="block text-center bg-gold hover:bg-yellow-500 text-black py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all">
                    <i class="fab fa-whatsapp mr-2 text-lg"></i> Konfirmasi Pembayaran
                </a>
            </div>
        </div>

        <footer class="py-10 text-center opacity-30">
            <p class="text-[10px] font-bold tracking-[0.3em] uppercase italic">K3STORE Digital Delivery System</p>
        </footer>
    </main>

</body>
</html>