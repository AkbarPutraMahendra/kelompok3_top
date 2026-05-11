<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'customers';

    // Sesuai rancangan PDM: Primary Key adalah id_customer
    protected $primaryKey = 'id_customer';

    // Kolom yang boleh diisi (mass assignable)
    protected $fillable = [
        'email',
        'id_akun',
        'id_game',
    ];

    /**
     * Relasi: Setiap customer/transaksi terikat pada satu Game tertentu.
     */
    public function game()
    {
        return $this->belongsTo(Game::class, 'id_game', 'id_game');
    }
}