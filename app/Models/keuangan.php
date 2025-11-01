<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keuangan extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaksi_id',
        'totalpemesanan',
        'tanggal',
    ];

    protected $casts = [
        'totalpemesanan' => 'decimal:2',
        'tanggal' => 'date',
    ];

    /**
     * Relasi ke Transaksi
     */
    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }
}