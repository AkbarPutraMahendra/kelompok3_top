<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            // Menambah kolom nominal dan metode_pembayaran
            $table->string('nominal')->after('id_customer')->nullable();
            $table->string('metode_pembayaran')->after('nominal')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            // Menghapus kolom jika rollback
            $table->dropColumn(['nominal', 'metode_pembayaran']);
        });
    }
};