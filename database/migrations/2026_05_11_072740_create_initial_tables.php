<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi untuk membuat tabel games dan customers.
     */
    public function up(): void
    {
        // 1. Membuat Tabel Games (Sesuai Rancangan PDM)
        Schema::create('games', function (Blueprint $table) {
            $table->id('id_game'); // Primary Key [cite: 81]
            $table->string('nama_game'); // Nama Game [cite: 86]
            $table->timestamps();
        });

        // 2. Membuat Tabel Customers (Sesuai Rancangan PDM)
        Schema::create('customers', function (Blueprint $table) {
            $table->id('id_customer'); // Primary Key [cite: 75]
            $table->string('email'); // Email pembeli [cite: 76]
            $table->string('id_akun'); // ID Akun Game/User ID [cite: 78]
            $table->unsignedBigInteger('id_game'); // Foreign Key ke tabel games [cite: 77]
            $table->timestamps();

            // Menghubungkan id_game di tabel ini ke id_game di tabel games 
            $table->foreign('id_game')->references('id_game')->on('games')->onDelete('cascade');
        });
    }

    /**
     * Batalkan migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
        Schema::dropIfExists('games');
    }
};