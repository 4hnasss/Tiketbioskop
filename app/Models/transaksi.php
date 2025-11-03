<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'jadwal_id',
        'kursi',
        'totalharga',
        'status',
        'metode_bayar',
        'tanggaltransaksi',
    ];

    protected $casts = [
        'kursi' => 'array',
        'totalharga' => 'decimal:2',
        'tanggaltransaksi' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class);
    }

    /**
     * Relasi ke Keuangan
     */
    public function keuangan()
    {
        return $this->hasOne(Keuangan::class);
    }

    public function tikets()
    {
        return $this->hasMany(Tiket::class);
    }
}