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

        // 2. Tabel Transaksi (Inti dari Flowchart Anda)
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id('id_transaksi');
            $table->unsignedBigInteger('id_customer');
            $table->dateTime('tanggal');
            $table->string('status')->default('Pending'); // Pending, Sukses, Gagal
            $table->timestamps();

            // Relasi ke tabel customers
            $table->foreign('id_customer')->references('id_customer')->on('customers')->onDelete('cascade');
        });

        // 3. Tabel Pembayaran
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id('id_pembayaran');
            $table->unsignedBigInteger('id_transaksi');
            $table->unsignedBigInteger('id_metode');
            $table->decimal('jumlah', 15, 2); // Nominal top-up
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