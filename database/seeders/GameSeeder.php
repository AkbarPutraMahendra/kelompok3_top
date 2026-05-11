<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GameSeeder extends Seeder
{
    public function run(): void
    {
        // Data game awal untuk platform top-up Anda
        $games = [
            ['nama_game' => 'Mobile Legends'],
            ['nama_game' => 'Free Fire'],
            ['nama_game' => 'Genshin Impact'],
            ['nama_game' => 'PUBG Mobile'],
            ['nama_game' => 'Valorant'],
        ];

        // Masukkan data ke tabel games sesuai rancangan PDM [cite: 74, 80]
        DB::table('games')->insert($games);
    }
}