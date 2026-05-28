<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Memanggil GameSeeder untuk mengisi daftar game
        $this->call([
            GameSeeder::class,
        ]);

        // 2. Mengisi Metode Pembayaran (Menggunakan updateOrInsert agar tidak duplikat)
        foreach (['QRIS', 'Transfer Bank', 'E-Wallet (OVO/Dana)'] as $metode) {
            DB::table('metode_pembayaran')->updateOrInsert(
                ['nama_metode' => $metode],
                ['created_at' => now(), 'updated_at' => now()]
            );
        }

        // 3. Membuat atau Update akun Admin K3 STORE (Anti-Duplicate)
        User::updateOrCreate(
            ['email' => 'admin@k3store.com'], // Email untuk login admin kamu
            [
                'name' => 'Admin K3 Store',
                'password' => bcrypt('admin12345'), // Password untuk login admin kamu
            ]
        );
    }
}