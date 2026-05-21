<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    // ==========================================
    // MANAGEMENT PENGATURAN KONTAK & QRIS
    // ==========================================
    
    /**
     * Menampilkan Halaman Pengaturan di Admin
     */
    public function indexPengaturan() {
        // Ambil data pengaturan pertama
        $config = DB::table('pengaturan')->first();
        
        // JIKA TABEL KOSONG, OTOMATIS GENERATE DATA PANCINGAN
        if (!$config) {
            DB::table('pengaturan')->insert([
                'id' => 1,
                'no_dana' => '08123456789',
                'qris_path' => 'qris.png',
                'created_at' => now(),
                'updated_at' => now()
            ]);
            $config = DB::table('pengaturan')->first();
        }
        
        return view('admin.pengaturan', compact('config'));
    }

    /**
     * Memproses Update QRIS dan No DANA dari Form Web Admin
     */
    public function updatePengaturan(Request $request) {
        $data = [
            'no_dana' => $request->no_dana, 
            'updated_at' => now()
        ];

        // Jika admin mengunggah file gambar QRIS baru
        if ($request->hasFile('qris')) {
            $request->validate([
                'qris' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048'
            ]);
            
            // Membuat nama file unik berdasarkan waktu agar tidak bentrok di browser
            $qrisName = 'qris_' . time() . '.' . $request->qris->getClientOriginalExtension();  
            $request->qris->move(public_path('images'), $qrisName);
            
            $data['qris_path'] = $qrisName;
        }

        // Kunci pembaruan data pada ID ke-1 yang dibaca oleh web user
        DB::table('pengaturan')->where('id', 1)->update($data);
        
        return back()->with('success', 'Kontak Pembayaran & QRIS berhasil diperbarui!');
    }
}