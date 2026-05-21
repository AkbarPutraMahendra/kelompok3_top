@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-8">
        <h2 class="text-2xl font-black uppercase italic tracking-widest text-[#fbbf24]">Kontak Pembayaran</h2>
        <p class="text-xs text-gray-500 mt-1 uppercase tracking-wider">Ubah metode tujuan transfer dan informasi pembayaran pelanggan</p>
    </div>

    <div class="bg-[#1f1f1f] border border-gray-800 rounded-[2rem] p-8 shadow-2xl">
        <form action="{{ route('admin.pengaturan.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-6">
                    <h3 class="text-xs font-black uppercase tracking-widest text-gray-400 flex items-center gap-2">
                        <i class="fa fa-wallet text-[#fbbf24]"></i> Akun Dompet Digital
                    </h3>
                    
                    <div>
                        <label class="text-[10px] text-gray-500 uppercase font-black tracking-wider block mb-2">Nomor DANA Store</label>
                        <input type="text" name="no_dana" value="{{ $config->no_dana ?? '' }}" placeholder="Contoh: 0812-3456-7890" class="w-full bg-gray-900 border border-gray-700 p-3.5 rounded-xl text-xs text-white font-mono outline-none focus:border-[#fbbf24] transition-all" required>
                    </div>

                    <div class="p-4 bg-yellow-500/5 border border-yellow-500/10 rounded-xl">
                        <p class="text-[10px] text-[#fbbf24] font-black uppercase tracking-wider flex items-center gap-2 mb-1">
                            <i class="fa fa-info-circle"></i> Info Admin
                        </p>
                        <p class="text-[10px] text-gray-400 leading-relaxed uppercase">
                            Nomor ini akan otomatis tampil di halaman instruksi pembayaran pelanggan sesaat setelah mereka menekan tombol beli sekarang.
                        </p>
                    </div>
                </div>

                <div class="space-y-6">
                    <h3 class="text-xs font-black uppercase tracking-widest text-gray-400 flex items-center gap-2">
                        <i class="fa fa-qrcode text-[#fbbf24]"></i> Kode QRIS Gateway
                    </h3>

                    <div class="grid grid-cols-3 gap-4 items-center">
                        <div class="col-span-1 bg-black/40 p-2 rounded-xl border border-gray-800 text-center">
                            <p class="text-[8px] text-gray-500 font-bold uppercase mb-2 tracking-wider">QRIS Aktif</p>
                            @if(isset($config->qris_path) && $config->qris_path != '')
                                <img id="qris-current" src="{{ asset('images/' . $config->qris_path) }}" class="w-full h-auto object-contain rounded-lg border border-gray-700 bg-white">
                            @else
                                <div class="w-full aspect-square bg-gray-900 rounded-lg flex items-center justify-center border border-gray-700">
                                    <i class="fa fa-qrcode text-xl text-gray-700"></i>
                                </div>
                            @endif
                        </div>

                        <div class="col-span-2">
                            <label class="text-[10px] text-gray-500 uppercase font-black tracking-wider block mb-2">Upload QRIS Baru</label>
                            <div class="relative w-full bg-gray-900 border border-dashed border-gray-700 p-4 rounded-xl text-center hover:border-[#fbbf24] transition-all cursor-pointer group">
                                <input type="file" name="qris" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" id="qrisInput" onchange="previewQris(event)">
                                <div id="qris-placeholder" class="space-y-1">
                                    <i class="fa fa-cloud-upload-alt text-lg text-gray-600 group-hover:text-[#fbbf24] transition-colors"></i>
                                    <p class="text-[9px] text-gray-400 font-bold uppercase tracking-wider">Ganti File QRIS</p>
                                </div>
                                <img id="qris-preview" class="hidden w-16 h-16 mx-auto object-contain rounded border border-gray-700 bg-white shadow-md">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-800 pt-6 flex justify-end">
                <button type="submit" class="bg-[#fbbf24] hover:bg-yellow-500 text-black text-xs font-black px-8 py-3.5 rounded-xl uppercase tracking-widest transition-all shadow-lg active:scale-95 flex items-center gap-2">
                    <i class="fa fa-save"></i> Perbarui Informasi Kontak
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function previewQris(event) {
        var reader = new FileReader();
        reader.onload = function(){
            var output = document.getElementById('qris-preview');
            var placeholder = document.getElementById('qris-placeholder');
            output.src = reader.result;
            output.classList.remove('hidden');
            placeholder.classList.add('hidden');
        }
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
@endsection