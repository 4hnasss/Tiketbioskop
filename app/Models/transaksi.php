<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class transaksi extends Model
{
    protected $fillable = [
        'user_id',
        'jadwal_id',
        'tanggaltransaksi',
        'totalharga',
        'status',
        'snap_token',
        'kursi',
    ];

    protected $casts = [
        'kursi' => 'array',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

// app/Models/Transaksi.php (tambahkan relasi)

    public function tikets()
    {
        return $this->hasMany(Tiket::class);
    }

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class);
    }

    public function kursi()
    {
        return $this->belongsToMany(Kursi::class);
    }

    public function keuangan(){
        return $this->belongsTo(keuangan::class);
    }

}
