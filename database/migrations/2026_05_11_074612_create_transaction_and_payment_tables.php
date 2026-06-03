<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabel Metode Pembayaran (Qris, Transfer, dll)
        Schema::create('metode_pembayaran', function (Blueprint $table) {
            $table->id('id_metode');
            $table->string('nama_metode'); // Contoh: QRIS, Bank Mandiri
            $table->timestamps();
        });

        // 2. Tabel Transaksi 
        // Ditambahkan kolom user_id, zone_id, id_game, nominal, dan email agar data top-up terekam sempurna.
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id('id_transaksi');
            $table->string('no_transaksi')->unique(); // Kode unik pelacakan (Contoh: TRX-20260511-XXXXX)
            $table->unsignedBigInteger('id_customer');
            
            // --- KOLOM TAMBAHAN UNTUK DATA TOP-UP (WAJIB ADA) ---
            $table->string('id_game');                // Mencatat ID game yang dituju (Contoh: mobile_legends)
            $table->string('user_id');                // Mencatat ID Player/User ID akun game
            $table->string('zone_id')->nullable();    // SINKRONISASI: Dibuat nullable agar game non-MLBB bisa kosong
            $table->string('email');                  // Mencatat email pengiriman nota
            $table->string('nominal');                // Nama paket/layanan yang dibeli (Contoh: 86 Diamonds)
            // ----------------------------------------------------

            $table->dateTime('tanggal');
            $table->string('status')->default('Pending'); // Pending, Sukses, Gagal
            $table->timestamps();

            // Relasi ke tabel customers (Pastikan tabel customers sudah dimigrasi terlebih dahulu)
            $table->foreign('id_customer')->references('id_customer')->on('customers')->onDelete('cascade');
        });

        // 3. Tabel Pembayaran
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id('id_pembayaran');
            $table->unsignedBigInteger('id_transaksi');
            $table->unsignedBigInteger('id_metode');
            $table->decimal('jumlah', 15, 2); // Nominal harga yang harus dibayar pembeli
            $table->timestamps();

            $table->foreign('id_transaksi')->references('id_transaksi')->on('transaksi')->onDelete('cascade');
            $table->foreign('id_metode')->references('id_metode')->on('metode_pembayaran');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
        Schema::dropIfExists('transaksi');
        Schema::dropIfExists('metode_pembayaran');
    }
};