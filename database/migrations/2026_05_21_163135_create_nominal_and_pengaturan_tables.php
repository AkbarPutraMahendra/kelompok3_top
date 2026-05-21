<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Tabel untuk Nominal dan Harga (Relasi ke tabel games)
        Schema::create('nominal_games', function (Blueprint $table) {
            $table->id('id_nominal');
            $table->unsignedBigInteger('id_game'); // Sesuaikan tipe datanya dengan id_game di tabel games Anda
            $table->string('layanan'); // Contoh: "86 Diamonds" atau "80 Robux"
            $table->integer('harga'); // Simpan angka saja, misal: 22000
            $table->integer('stok')->default(0); // Tambah fitur stok
            $table->timestamps();

            // Setup foreign key ke tabel games
            $table->foreign('id_game')->references('id_game')->on('games')->onDelete('cascade');
        });

        // Tabel Pengaturan Kontak & QRIS (Hanya butuh 1 baris data)
        Schema::create('pengaturan', function (Blueprint $table) {
            $table->id();
            $table->string('no_dana')->nullable();
            $table->string('qris_path')->nullable(); // Untuk simpan nama file gambar QRIS
            $table->timestamps();
        });

        // Isi data default untuk kontak agar tidak kosong saat dipanggil
        DB::table('pengaturan')->insert([
            'no_dana' => '0812-3456-7890',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('nominal_games');
        Schema::dropIfExists('pengaturan');
    }
};