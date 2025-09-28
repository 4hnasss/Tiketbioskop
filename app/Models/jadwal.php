<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class jadwal extends Model
{
    protected $fillable = [
        'film_id',
        'studio_id',
        'harga_id',
        'tanggal',
        'jamtayang',
    ];

    public function transaksi(){
        return $this->belongsTo(transaksi::class);
    }

    public function harga(){
        return $this->belongsTo(harga::class);
    }

    public function studio(){
        return $this->belongsTo(studio::class);
    }

    public function film(){
        return $this->belongsTo(film::class);
    }
}
