<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>K3STORE - Admin Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-[#121212] min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md bg-[#1f1f1f] border border-gray-800 p-8 rounded-[2.5rem] shadow-2xl relative overflow-hidden">
        <div class="absolute -top-10 -right-10 w-32 h-32 bg-[#fbbf24]/10 rounded-full blur-xl"></div>
        
        <div class="text-center mb-8 relative">
            <h1 class="text-3xl font-black tracking-widest italic text-[#fbbf24]">K3<span class="text-white">STORE</span></h1>
            <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest mt-1">Halaman Otentikasi Admin</p>
        </div>

        @if (session('status'))
        <div class="mb-5 p-3.5 bg-green-500/10 border border-green-500/30 rounded-xl text-green-400 text-xs font-bold uppercase tracking-wider flex items-center gap-2">
            <i class="fa fa-check-circle text-sm"></i>
            <span>{{ session('status') }}</span>
        </div>
        @endif

        @if($errors && $errors->any())
        <div class="mb-5 p-3.5 bg-red-500/10 border border-red-500/30 rounded-xl text-red-400 text-xs font-bold uppercase tracking-wider flex flex-col gap-1">
            @foreach ($errors->all() as $error)
                <div class="flex items-center gap-2">
                    <i class="fa fa-exclamation-circle text-sm"></i>
                    <span>{{ $error }}</span>
                </div>
            @endforeach
        </div>
        @endif

        <form action="{{ route('login') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label class="text-[10px] text-gray-400 uppercase font-black tracking-wider block mb-2">Alamat Email</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-600">
                        <i class="fa fa-envelope text-xs"></i>
                    </span>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="admin@k3store.com" class="w-full bg-gray-900 border border-gray-700 pl-9 p-3 rounded-xl text-xs text-white outline-none focus:border-[#fbbf24] transition-all font-semibold" required autofocus autocomplete="username">
                </div>
            </div>

            <div>
                <label class="text-[10px] text-gray-400 uppercase font-black tracking-wider block mb-2">Kata Sandi</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-600">
                        <i class="fa fa-lock text-xs"></i>
                    </span>
                    
                    <input type="password" name="password" id="password" placeholder="••••••••" class="w-full bg-gray-900 border border-gray-700 pl-9 pr-10 p-3 rounded-xl text-xs text-white outline-none focus:border-[#fbbf24] transition-all font-semibold" required autocomplete="current-password">
                    
                    <button type="button" onclick="togglePasswordVisibility()" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-500 hover:text-[#fbbf24] transition-colors focus:outline-none">
                        <i id="eye-icon" class="fa fa-eye text-xs"></i>
                    </button>
                </div>
            </div>

            <div class="flex items-center justify-between pt-1">
                <label class="flex items-center gap-2 cursor-pointer select-none">
                    <input type="checkbox" name="remember" class="accent-[#fbbf24] bg-gray-900 border-gray-700 rounded cursor-pointer">
                    <span class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Ingat Saya</span>
                </label>
            </div>

            <button type="submit" class="w-full bg-[#fbbf24] hover:bg-yellow-500 text-black text-xs font-black py-3.5 rounded-xl uppercase tracking-widest transition-all shadow-lg active:scale-95 flex items-center justify-center gap-2 mt-2">
                <i class="fa fa-sign-in-alt text-sm"></i> Masuk Panel
            </button>
        </form>

        <div class="mt-8 text-center">
            <a href="{{ url('/') }}" class="text-[10px] font-black uppercase text-gray-600 hover:text-gray-400 tracking-widest transition-colors"><i class="fa fa-arrow-left text-[9px]"></i> Kembali ke Toko</a>
        </div>
    </div>

    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                // Ubah ikon ke mata dicoret (fa-eye-slash)
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                // Kembalikan ke ikon mata normal (fa-eye)
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }
    </script>

</body>
</html>