@extends('layouts.admin')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="mb-8">
        <h2 class="text-2xl font-black uppercase italic tracking-widest text-[#fbbf24]">Daftar Games</h2>
        <p class="text-xs text-gray-500 mt-1 uppercase tracking-wider">Kelola data game yang aktif ditayangkan pada website</p>
    </div>

    @if(session('success'))
    <div class="mb-6 p-4 bg-green-500/10 border border-green-500/30 rounded-xl text-green-400 text-xs font-bold uppercase tracking-wider">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 p-4 bg-red-500/10 border border-red-500/30 rounded-xl text-red-400 text-xs font-bold uppercase tracking-wider">
        {{ session('error') }}
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="bg-[#1f1f1f] p-6 rounded-[2rem] border border-gray-800 h-fit shadow-xl">
            <h3 class="text-xs font-black uppercase tracking-widest text-gray-400 mb-6 flex items-center gap-2">
                <i class="fa fa-plus-circle text-[#fbbf24]"></i> Tambah Game Baru
            </h3>
            <form action="{{ route('admin.games.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf
                <div>
                    <label class="text-[10px] text-gray-500 uppercase font-black tracking-wider block mb-2">Nama Game</label>
                    <input type="text" name="nama_game" placeholder="Contoh: Mobile Legends, Roblox" class="w-full bg-gray-900 border border-gray-700 p-3 rounded-xl text-xs text-white outline-none focus:border-[#fbbf24] transition-all font-semibold" required>
                </div>
                
                {{-- Dropdown Tipe Form di Form Tambah --}}
                <div>
                    <label class="text-[10px] text-gray-500 uppercase font-black tracking-wider block mb-2">Tipe Inputan Player</label>
                    <select name="tipe_form" class="w-full bg-gray-900 border border-gray-700 p-3 rounded-xl text-xs text-white outline-none focus:border-[#fbbf24] transition-all font-semibold" required>
                        <option value="single">1 Kolom (Hanya User ID - Contoh: Free Fire, Valorant, Roblox)</option>
                        <option value="double">2 Kolom (User ID + Zone ID - Contoh: Mobile Legends)</option>
                    </select>
                </div>

                <div>
                    <label class="text-[10px] text-gray-500 uppercase font-black tracking-wider block mb-2">Banner / Gambar Game</label>
                    <div class="relative w-full bg-gray-900 border border-dashed border-gray-700 p-4 rounded-xl text-center hover:border-[#fbbf24] transition-all cursor-pointer group">
                        <input type="file" name="gambar_game" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" required id="gameImgInput" onchange="previewImage(event)">
                        <div id="preview-placeholder" class="space-y-1">
                            <i class="fa fa-image text-xl text-gray-600 group-hover:text-[#fbbf24] transition-colors"></i>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Pilih File Gambar</p>
                            <p class="text-[9px] text-gray-600">Format PNG, JPG (Max 2MB)</p>
                        </div>
                        <img id="img-preview" class="hidden w-24 h-24 mx-auto object-cover rounded-xl border border-gray-700 shadow-md">
                    </div>
                </div>
                <button type="submit" class="w-full bg-[#fbbf24] hover:bg-yellow-500 text-black text-xs font-black py-3.5 rounded-xl uppercase tracking-widest transition-all shadow-lg active:scale-95">
                    Simpan Game
                </button>
            </form>
        </div>

        <div class="lg:col-span-2 bg-[#1f1f1f] rounded-[2rem] border border-gray-800 overflow-hidden shadow-xl">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-black/40 text-[10px] uppercase tracking-[0.2em] text-gray-500">
                            <th class="p-6">Gambar</th>
                            <th class="p-6">Nama Game</th>
                            <th class="p-6">Tipe Form</th>
                            <th class="p-6 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800 text-xs">
                        @foreach($games as $game)
                        <tr class="hover:bg-white/[0.01] transition-colors">
                            <td class="p-6 w-24">
                                <img src="{{ asset('images/' . $game->id_game . '.png') }}" 
                                     onerror="this.onerror=null; this.src='{{ asset('images/' . $game->id_game . '.jpg') }}';" 
                                     alt="{{ $game->nama_game }}" 
                                     class="w-12 h-12 object-cover rounded-xl border border-gray-700 bg-gray-900">
                            </td>
                            <td class="p-6 font-black uppercase italic tracking-wider text-gray-200">
                                {{ $game->nama_game }}
                            </td>
                            {{-- Status Informasi Kolom di Tabel --}}
                            <td class="p-6 font-semibold uppercase text-[10px] tracking-wider">
                                @if(($game->tipe_form ?? 'single') === 'double')
                                    <span class="px-2.5 py-1 bg-yellow-500/10 text-[#fbbf24] rounded-md border border-[#fbbf24]/20">2 Kolom (MLBB)</span>
                                @else
                                    <span class="px-2.5 py-1 bg-gray-800 text-gray-400 rounded-md border border-gray-700">1 Kolom (Biasa)</span>
                                @endif
                            </td>
                            <td class="p-6">
                                <div class="flex items-center justify-center gap-3">
                                    <button type="button" 
                                            onclick="openEditModal('{{ $game->id_game }}', '{{ $game->nama_game }}', '{{ $game->tipe_form ?? 'single' }}')" 
                                            class="w-8 h-8 bg-blue-500/10 hover:bg-blue-500 text-blue-400 hover:text-white rounded-lg flex items-center justify-center transition-all shadow-md active:scale-95">
                                        <i class="fa fa-edit text-xs"></i>
                                    </button>

                                    <form action="{{ route('admin.games.destroy', $game->id_game) }}" method="POST" onsubmit="return confirm('Hapus game ini? Semua nominal terkait juga akan terhapus!')">
                                        @csrf 
                                        @method('DELETE')
                                        <button type="submit" class="w-8 h-8 bg-red-500/10 hover:bg-red-500 text-red-500 hover:text-white rounded-lg flex items-center justify-center transition-all shadow-md active:scale-95">
                                            <i class="fa fa-trash-alt text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if(count($games) == 0)
            <div class="p-20 text-center">
                <i class="fa fa-gamepad text-4xl text-gray-800 mb-4"></i>
                <p class="text-xs text-gray-600 font-bold uppercase tracking-[0.3em]">Belum ada data game</p>
            </div>
            @endif
        </div>
    </div>
</div>

<div id="editGameModal" class="fixed inset-0 z-50 hidden bg-black/70 backdrop-blur-sm flex items-center justify-center p-4 animate-fade-in">
    <div class="bg-[#1f1f1f] border border-gray-800 rounded-[2rem] max-w-md w-full overflow-hidden shadow-2xl transform transition-all p-6">
        
        <div class="flex justify-between items-center mb-6 pb-4 border-b border-gray-800">
            <h3 class="text-xs font-black uppercase tracking-widest text-[#fbbf24] flex items-center gap-2">
                <i class="fa fa-edit"></i> Edit Data Game
            </h3>
            <button type="button" onclick="closeEditModal()" class="text-gray-500 hover:text-white transition-colors">
                <i class="fa fa-times text-sm"></i>
            </button>
        </div>
        
        <form id="editGameForm" action="" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT') 
            <div>
                <label class="text-[10px] text-gray-500 uppercase font-black tracking-wider block mb-2">Nama Game</label>
                <input type="text" id="edit_nama_game" name="nama_game" required 
                       class="w-full bg-gray-900 border border-gray-700 p-3 rounded-xl text-xs text-white outline-none focus:border-[#fbbf24] transition-all font-semibold">
            </div>

            {{-- Dropdown Tipe Form di dalam Modal Edit --}}
            <div>
                <label class="text-[10px] text-gray-500 uppercase font-black tracking-wider block mb-2">Tipe Inputan Player</label>
                <select id="edit_tipe_form" name="tipe_form" class="w-full bg-gray-900 border border-gray-700 p-3 rounded-xl text-xs text-white outline-none focus:border-[#fbbf24] transition-all font-semibold" required>
                    <option value="single">1 Kolom (Hanya User ID - Contoh: Free Fire, Valorant, Roblox)</option>
                    <option value="double">2 Kolom (User ID + Zone ID - Contoh: Mobile Legends)</option>
                </select>
            </div>

            <div>
                <label class="text-[10px] text-gray-500 uppercase font-black tracking-wider block mb-2">Ganti Banner / Gambar (Opsional)</label>
                <div class="relative w-full bg-gray-900 border border-dashed border-gray-700 p-4 rounded-xl text-center hover:border-[#fbbf24] transition-all cursor-pointer group">
                    <input type="file" name="gambar_game" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" id="editGameImgInput" onchange="previewEditImage(event)">
                    <div id="edit-preview-placeholder" class="space-y-1">
                        <i class="fa fa-image text-xl text-gray-600 group-hover:text-[#fbbf24] transition-colors"></i>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Pilih Gambar Baru</p>
                        <p class="text-[9px] text-gray-600">Kosongkan jika tidak ingin mengubah poster lama</p>
                    </div>
                    <img id="edit-img-preview" class="hidden w-24 h-24 mx-auto object-cover rounded-xl border border-gray-700 shadow-md">
                </div>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closeEditModal()" 
                        class="w-1/2 bg-gray-800 hover:bg-gray-700 text-gray-300 text-xs font-black py-3.5 rounded-xl uppercase tracking-widest transition-all">
                    Batal
                </button>
                <button type="submit" 
                        class="w-1/2 bg-[#fbbf24] hover:bg-yellow-500 text-black text-xs font-black py-3.5 rounded-xl uppercase tracking-widest transition-all shadow-lg">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Preview gambar untuk Form Tambah Game
    function previewImage(event) {
        var reader = new FileReader();
        reader.onload = function(){
            var output = document.getElementById('img-preview');
            var placeholder = document.getElementById('preview-placeholder');
            output.src = reader.result;
            output.classList.remove('hidden');
            placeholder.classList.add('hidden');
        }
        reader.readAsDataURL(event.target.files[0]);
    }

    // Preview gambar untuk Form Edit Game
    function previewEditImage(event) {
        var reader = new FileReader();
        reader.onload = function(){
            var output = document.getElementById('edit-img-preview');
            var placeholder = document.getElementById('edit-preview-placeholder');
            output.src = reader.result;
            output.classList.remove('hidden');
            placeholder.classList.add('hidden');
        }
        reader.readAsDataURL(event.target.files[0]);
    }

    // Fungsi membuka modal edit dan menaruh data lama secara dinamis
    function openEditModal(id, namaGame, tipeForm) {
        const modal = document.getElementById('editGameModal');
        const form = document.getElementById('editGameForm');
        const inputNama = document.getElementById('edit_nama_game');
        const inputTipe = document.getElementById('edit_tipe_form');
        
        // Isikan value nama game lama ke form input
        inputNama.value = namaGame;
        
        // Isikan data tipe_form lama ke dalam elemen select di modal
        inputTipe.value = tipeForm;
        
        // SINKRONISASI: Mengubah rute agar sesuai prefix admin dan pattern PUT /admin/games/{id}
        form.action = `/admin/games/${id}`;
        
        // Reset preview file di dalam modal saat dibuka kembali
        document.getElementById('edit-img-preview').classList.add('hidden');
        document.getElementById('edit-preview-placeholder').classList.remove('hidden');
        document.getElementById('editGameImgInput').value = "";

        // Tampilkan modal
        modal.classList.remove('hidden');
    }

    // Fungsi menutup modal
    function closeEditModal() {
        document.getElementById('editGameModal').classList.add('hidden');
    }
</script>
@endsection