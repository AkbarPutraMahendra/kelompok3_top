<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel | K3 STORE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-[#121212] text-white font-sans antialiased flex">

    <aside class="w-64 min-h-screen bg-[#1f1f1f] border-r border-gray-800 flex flex-col fixed left-0 top-0 z-50">
        <div class="p-6 border-b border-gray-800 flex items-center gap-2">
            <div class="bg-[#fbbf24] p-1.5 rounded-lg">
                <i class="fa fa-bolt text-black text-sm"></i>
            </div>
            <h1 class="text-xl font-black italic uppercase tracking-tighter">K3<span class="text-[#fbbf24]">ADMIN</span></h1>
        </div>
        
        <nav class="flex-1 p-4 space-y-2">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-bold uppercase tracking-wider transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-[#fbbf24] text-black' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
                <i class="fa fa-chart-pie text-sm"></i> Dashboard Utama
            </a>

            <a href="{{ route('admin.pesanan.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-bold uppercase tracking-wider transition-all {{ request()->routeIs('admin.pesanan.*') ? 'bg-[#fbbf24] text-black' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
                <i class="fa fa-shopping-cart text-sm"></i> Kelola Pesanan
            </a>

            <a href="{{ route('admin.games.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-bold uppercase tracking-wider transition-all {{ request()->routeIs('admin.games.*') ? 'bg-[#fbbf24] text-black' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
                <i class="fa fa-gamepad text-sm"></i> Daftar Games
            </a>

            <a href="{{ route('admin.nominal.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-bold uppercase tracking-wider transition-all {{ request()->routeIs('admin.nominal.*') ? 'bg-[#fbbf24] text-black' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
                <i class="fa fa-tags text-sm"></i> Isi Game & Stok
            </a>

            <a href="{{ route('admin.pengaturan.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-bold uppercase tracking-wider transition-all {{ request()->routeIs('admin.pengaturan.*') ? 'bg-[#fbbf24] text-black' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
                <i class="fa fa-wrench text-sm"></i> Kontak Pembayaran
            </a>

            <a href="{{ route('admin.register.form') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-bold uppercase tracking-wider transition-all {{ request()->routeIs('admin.register.form') ? 'bg-[#fbbf24] text-black' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
                <i class="fa fa-user-plus text-sm"></i> Tambah Akun Admin
            </a>
        </nav>

        <div class="p-4 border-t border-gray-800 space-y-2">
            <a href="{{ route('home') }}" target="_blank" class="flex items-center justify-center gap-2 w-full bg-white/5 hover:bg-white/10 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">
                <i class="fa fa-external-link-alt"></i> Lihat Toko
            </a>

            <a href="#" 
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
               class="flex items-center justify-center gap-2 w-full bg-red-500/10 hover:bg-red-600 text-red-400 hover:text-white py-3 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">
                <i class="fa fa-sign-out-alt"></i> Keluar Sistem
            </a>

            <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="hidden">
                @csrf
            </form>
        </div>
    </aside>

    <div class="flex-1 pl-64">
        <header class="bg-[#1f1f1f]/50 backdrop-blur py-4 px-8 border-b border-gray-800 flex justify-end items-center sticky top-0 z-40">
            <div class="flex items-center gap-2 text-xs font-bold text-gray-400">
                <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                Administrator Mode
            </div>
        </header>

        <main class="p-8">
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-500/10 border border-green-500/20 text-green-500 rounded-xl text-xs font-bold uppercase tracking-wider flex items-center gap-2">
                    <i class="fa fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 bg-red-500/10 border border-red-500/20 text-red-500 rounded-xl text-xs font-bold uppercase tracking-wider flex items-center gap-2">
                    <i class="fa fa-exclamation-circle"></i> {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>

</body>
</html>