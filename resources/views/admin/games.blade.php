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
                <div>
                    <label class="text-[10px] text-gray-500 uppercase font-black tracking-wider block mb-2">Banner / Gambar Game</label>
                    <div class="relative w-full bg-gray-900 border border-dashed border-gray-700 p-4 rounded-xl text-center hover:border-[#fbbf24] transition-all cursor-pointer group">
                        <input type="file" name="gambar" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" required id="gameImgInput" onchange="previewImage(event)">
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
                            <td class="p-6">
                                <div class="flex items-center justify-center gap-3">
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

<script>
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
</script>
@endsection