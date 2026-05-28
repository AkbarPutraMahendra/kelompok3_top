@extends('layouts.admin')

@section('content')
<div class="max-w-xl mx-auto">
    
    <div class="mb-6">
        <h2 class="text-2xl font-black uppercase tracking-tight italic">Registrasi <span class="text-[#fbbf24]">Admin Baru</span></h2>
        <p class="text-xs text-gray-400 font-medium uppercase tracking-wider mt-1">Daftarkan akun administrator pelaksana baru untuk K3 STORE</p>
    </div>

    <div class="bg-[#1f1f1f] border border-gray-800 rounded-2xl p-6 shadow-xl">
        <form action="{{ route('admin.register.store') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label for="name" class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Nama Lengkap Admin</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-500">
                        <i class="fa fa-user text-xs"></i>
                    </span>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required 
                        class="w-full bg-[#121212] border @error('name') border-red-500 @else border-gray-800 @enderror focus:border-[#fbbf24] rounded-xl py-3 pl-11 pr-4 text-sm font-semibold tracking-wide text-white outline-none transition-all placeholder-gray-600" 
                        placeholder="Contoh: Alex Wijaya">
                </div>
                @error('name')
                    <p class="text-[11px] text-red-400 font-bold uppercase tracking-wide mt-1.5"><i class="fa fa-info-circle"></i> {{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Alamat Email Aktif</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-500">
                        <i class="fa fa-envelope text-xs"></i>
                    </span>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required 
                        class="w-full bg-[#121212] border @error('email') border-red-500 @else border-gray-800 @enderror focus:border-[#fbbf24] rounded-xl py-3 pl-11 pr-4 text-sm font-semibold tracking-wide text-white outline-none transition-all placeholder-gray-600" 
                        placeholder="admin.baru@k3store.com">
                </div>
                @error('email')
                    <p class="text-[11px] text-red-400 font-bold uppercase tracking-wide mt-1.5"><i class="fa fa-info-circle"></i> {{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Password Akun</label>
                <div class="relative flex items-center">
                    <span class="absolute left-0 flex items-center pl-4 text-gray-500 pointer-events-none">
                        <i class="fa fa-lock text-xs"></i>
                    </span>
                    <input type="password" name="password" id="password" required 
                        class="w-full bg-[#121212] border @error('password') border-red-500 @else border-gray-800 @enderror focus:border-[#fbbf24] rounded-xl py-3 pl-11 pr-24 text-sm font-semibold tracking-wide text-white outline-none transition-all placeholder-gray-600" 
                        placeholder="Minimal 8 karakter">
                    
                    <div class="absolute right-0 flex items-center pr-3 gap-2">
                        <button type="button" id="reset-pass" class="hidden text-gray-500 hover:text-red-400 transition-colors p-1" title="Bersihkan input">
                            <i class="fa fa-times-circle text-sm"></i>
                        </button>
                        <button type="button" id="toggle-pass" class="text-gray-500 hover:text-[#fbbf24] transition-colors p-1" title="Lihat password">
                            <i class="fa fa-eye text-sm" id="eye-icon"></i>
                        </button>
                    </div>
                </div>
                @error('password')
                    <p class="text-[11px] text-red-400 font-bold uppercase tracking-wide mt-1.5"><i class="fa fa-info-circle"></i> {{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Ulangi Password</label>
                <div class="relative flex items-center">
                    <span class="absolute left-0 flex items-center pl-4 text-gray-500 pointer-events-none">
                        <i class="fa fa-shield-alt text-xs"></i>
                    </span>
                    <input type="password" name="password_confirmation" id="password_confirmation" required 
                        class="w-full bg-[#121212] border border-gray-800 focus:border-[#fbbf24] rounded-xl py-3 pl-11 pr-12 text-sm font-semibold tracking-wide text-white outline-none transition-all placeholder-gray-600" 
                        placeholder="Masukkan kembali password">
                    
                    <button type="button" id="toggle-confirm-pass" class="absolute right-0 flex items-center pr-4 text-gray-500 hover:text-[#fbbf24] transition-colors p-1" title="Lihat password">
                        <i class="fa fa-eye text-sm" id="eye-confirm-icon"></i>
                    </button>
                </div>
            </div>

            <div class="pt-2 flex items-center justify-between gap-4">
                <a href="{{ route('admin.dashboard') }}" class="w-1/3 text-center bg-white/5 hover:bg-white/10 text-gray-300 font-bold uppercase tracking-wider text-xs py-3.5 rounded-xl transition-all">
                    Kembali
                </a>
                <button type="submit" class="w-2/3 bg-[#fbbf24] hover:bg-[#f5b014] text-black font-black uppercase tracking-widest text-xs py-3.5 rounded-xl transition-all shadow-md shadow-yellow-500/10">
                    <i class="fa fa-save me-1"></i> Daftarkan Sekarang
                </button>
            </div>

        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Elemen Password Utama
        const passwordInput = document.getElementById('password');
        const togglePassBtn = document.getElementById('toggle-pass');
        const eyeIcon = document.getElementById('eye-icon');
        const resetPassBtn = document.getElementById('reset-pass');

        // Elemen Konfirmasi Password
        const confirmInput = document.getElementById('password_confirmation');
        const toggleConfirmBtn = document.getElementById('toggle-confirm-pass');
        const eyeConfirmIcon = document.getElementById('eye-confirm-icon');

        // 1. Logic Muncul/Sembunyikan Tombol Reset (Hanya muncul jika ada teks)
        passwordInput.addEventListener('input', function () {
            if (this.value.length > 0) {
                resetPassBtn.classList.remove('hidden');
            } else {
                resetPassBtn.classList.add('hidden');
            }
        });

        // 2. Logic Aksi Tombol Reset (Menghapus isi kolom password)
        resetPassBtn.addEventListener('click', function () {
            passwordInput.value = ''; // Kosongkan text
            this.classList.add('hidden'); // Sembunyikan tombol reset lagi
            passwordInput.focus(); // Kembalikan kursor fokus ke inputan
        });

        // 3. Logic Toggle Mata Password Utama
        togglePassBtn.addEventListener('click', function () {
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
                this.title = "Sembunyikan password";
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
                this.title = "Lihat password";
            }
        });

        // 4. Logic Toggle Mata Konfirmasi Password
        toggleConfirmBtn.addEventListener('click', function () {
            if (confirmInput.type === 'password') {
                confirmInput.type = 'text';
                eyeConfirmIcon.classList.remove('fa-eye');
                eyeConfirmIcon.classList.add('fa-eye-slash');
                this.title = "Sembunyikan password";
            } else {
                confirmInput.type = 'password';
                eyeConfirmIcon.classList.remove('fa-eye-slash');
                eyeConfirmIcon.classList.add('fa-eye');
                this.title = "Lihat password";
            }
        });
    });
</script>
@endsection