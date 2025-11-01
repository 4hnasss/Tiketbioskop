<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tiket extends Model
{
    use HasFactory;

// app/Models/Tiket.php
    protected $fillable = [
        'transaksi_id',
        'kursi_id',
        'jadwal_id',
        'kodetiket'
    ];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }

    public function kursi()
    {
        return $this->belongsTo(Kursi::class);
    }

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class);
    }
}