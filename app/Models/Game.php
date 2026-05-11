<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    // Menentukan nama tabel (opsional, tapi aman untuk konsistensi)
    protected $table = 'games';

    // Sesuai rancangan PDM: Primary Key adalah id_game
    protected $primaryKey = 'id_game';

    // Kolom yang boleh diisi
    protected $fillable = ['nama_game'];

    /**
     * Relasi: Satu game bisa memiliki banyak data customer (transaksi).
     */
    public function customers()
    {
        return $this->hasMany(Customer::class, 'id_game', 'id_game');
    }
}